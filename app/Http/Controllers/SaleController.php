<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Sale;
use App\Services\BatchCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    public function __construct(
        private BatchCompletionService $batchCompletionService
    ) {}

    public function index(Business $business): Response
    {
        $business->load(['sales.productBatch', 'sales.soldBy']);

        $sales = $business->sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'product_batch' => $sale->productBatch,
                'sold_by' => $sale->soldBy,
                'quantity' => $sale->quantity,
                'sale_price' => $sale->sale_price,
                'sold_at' => $sale->sold_at,
            ];
        });

        return Inertia::render('Sales/Index', [
            'business' => $business,
            'sales' => $sales,
        ]);
    }

    public function create(Business $business): Response
    {
        $business->load(['productBatches' => function ($query) {
            $query->where('status', 'active');
        }]);

        return Inertia::render('Sales/Create', [
            'business' => $business,
        ]);
    }

    public function store(Request $request, Business $business): RedirectResponse
    {
        $request->validate([
            'product_batch_id' => 'required|exists:product_batches,id',
            'quantity' => 'required|integer|min:1',
            'sale_price' => 'required|numeric|min:0',
            'sold_at' => 'required|date',
        ]);

        // Check if batch has enough remaining quantity
        $batch = $business->productBatches()->findOrFail($request->product_batch_id);
        
        if ($batch->remaining_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough products available in this batch.']);
        }

        $sale = $business->sales()->create([
            'product_batch_id' => $request->product_batch_id,
            'sold_by' => auth()->id(),
            'quantity' => $request->quantity,
            'sale_price' => $request->sale_price,
            'sold_at' => $request->sold_at,
        ]);

        // Check if batch should be marked as completed
        $batch->refresh();
        $this->batchCompletionService->checkAndCompleteBatch($batch);

        return redirect()->route('businesses.sales.index', $business)
            ->with('success', 'Sale recorded successfully!');
    }

    public function show(Business $business, Sale $sale): Response
    {
        $sale->load(['productBatch', 'soldBy']);

        return Inertia::render('Sales/Show', [
            'business' => $business,
            'sale' => [
                'id' => $sale->id,
                'product_batch' => $sale->productBatch,
                'sold_by' => $sale->soldBy,
                'quantity' => $sale->quantity,
                'sale_price' => $sale->sale_price,
                'sold_at' => $sale->sold_at,
            ],
        ]);
    }

    public function edit(Business $business, Sale $sale): Response
    {
        $business->load(['productBatches' => function ($query) {
            $query->where('status', 'active');
        }]);

        return Inertia::render('Sales/Edit', [
            'business' => $business,
            'sale' => $sale,
        ]);
    }

    public function update(Request $request, Business $business, Sale $sale): RedirectResponse
    {
        $request->validate([
            'product_batch_id' => 'required|exists:product_batches,id',
            'quantity' => 'required|integer|min:1',
            'sale_price' => 'required|numeric|min:0',
            'sold_at' => 'required|date',
        ]);

        // Check if batch has enough remaining quantity (excluding current sale)
        $batch = $business->productBatches()->findOrFail($request->product_batch_id);
        $currentSaleQuantity = $sale->quantity;
        $availableQuantity = $batch->remaining_quantity + $currentSaleQuantity;
        
        if ($availableQuantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough products available in this batch.']);
        }

        $sale->update([
            'product_batch_id' => $request->product_batch_id,
            'quantity' => $request->quantity,
            'sale_price' => $request->sale_price,
            'sold_at' => $request->sold_at,
        ]);

        // Check if batch should be marked as completed
        $batch->refresh();
        $this->batchCompletionService->checkAndCompleteBatch($batch);

        return redirect()->route('businesses.sales.index', $business)
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Business $business, Sale $sale): RedirectResponse
    {
        $sale->delete();

        // Check if batch should be marked as completed after sale deletion
        if ($sale->productBatch) {
            $sale->productBatch->refresh();
            $this->batchCompletionService->checkAndCompleteBatch($sale->productBatch);
        }

        return redirect()->route('businesses.sales.index', $business)
            ->with('success', 'Sale deleted successfully!');
    }
}
