<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductBatch\StoreRequest;
use App\Http\Requests\ProductBatch\UpdateRequest;
use App\Models\Business;
use App\Models\ProductBatch;
use App\Services\BatchCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductBatchController extends Controller
{
    public function __construct(
        private BatchCompletionService $batchCompletionService
    ) {}

    public function index(Business $business): Response
    {
        $business->load(['productBatches.investments.user', 'productBatches.sales']);

        $batches = $business->productBatches->map(function ($batch) {
            return [
                'id' => $batch->id,
                'name' => $batch->name,
                'status' => $batch->status,
                'total_quantity' => $batch->total_quantity,
                'remaining_quantity' => $batch->remaining_quantity,
                'sold_percentage' => round($batch->sold_percentage, 2),
                'total_cost' => $batch->total_cost,
                'total_revenue' => $batch->total_revenue,
                'total_profit' => $batch->total_profit,
                'purchase_date' => $batch->purchase_date,
                'completion_date' => $batch->completion_date,
                'investors_count' => $batch->investments->count(),
                'total_investment' => $batch->investments->sum('amount'),
            ];
        });

        return Inertia::render('ProductBatches/Index', [
            'business' => $business,
            'batches' => $batches,
        ]);
    }

    public function create(Business $business): Response
    {
        return Inertia::render('ProductBatches/Create', [
            'business' => $business,
        ]);
    }

    public function store(StoreRequest $request, Business $business): RedirectResponse
    {
        $batch = $business->productBatches()->create([
            'name' => $request->name,
            'purchase_date' => $request->purchase_date,
            'total_cost' => $request->total_cost,
            'total_quantity' => $request->total_quantity,
            'status' => 'active',
        ]);

        return redirect()->route('businesses.product-batches.show', [$business, $batch])
            ->with('success', 'Product batch created successfully!');
    }

    public function show(Business $business, ProductBatch $productBatch): Response
    {
        $productBatch->load(['investments.user', 'sales.soldBy', 'profits']);

        $batchSummary = $this->batchCompletionService->getBatchSummary($productBatch);

        return Inertia::render('ProductBatches/Show', [
            'business' => $business,
            'batch' => $batchSummary,
        ]);
    }

    public function edit(Business $business, ProductBatch $productBatch): Response
    {
        return Inertia::render('ProductBatches/Edit', [
            'business' => $business,
            'batch' => $productBatch,
        ]);
    }

    public function update(UpdateRequest $request, Business $business, ProductBatch $productBatch): RedirectResponse
    {
        $productBatch->update([
            'name' => $request->name,
            'purchase_date' => $request->purchase_date,
            'total_cost' => $request->total_cost,
            'total_quantity' => $request->total_quantity,
            'status' => $request->status ?? $productBatch->status,
        ]);

        // Check if batch should be marked as completed
        if ($productBatch->remaining_quantity <= 0 && $productBatch->status === 'active') {
            $this->batchCompletionService->checkAndCompleteBatch($productBatch);
        }

        return redirect()->route('businesses.product-batches.show', [$business, $productBatch])
            ->with('success', 'Product batch updated successfully!');
    }

    public function destroy(Business $business, ProductBatch $productBatch): RedirectResponse
    {
        // Check if batch has any sales or investments
        if ($productBatch->sales()->count() > 0 || $productBatch->investments()->count() > 0) {
            return back()->with('error', 'Cannot delete batch with existing sales or investments.');
        }

        $productBatch->delete();

        return redirect()->route('businesses.product-batches.index', $business)
            ->with('success', 'Product batch deleted successfully!');
    }

    public function complete(Business $business, ProductBatch $productBatch): RedirectResponse
    {
        $wasCompleted = $this->batchCompletionService->checkAndCompleteBatch($productBatch);

        if ($wasCompleted) {
            return back()->with('success', 'Batch marked as completed! Profit calculations have been updated.');
        }

        return back()->with('info', 'Batch cannot be completed yet. All products must be sold first.');
    }

    public function calculateShares(Business $business, ProductBatch $productBatch): RedirectResponse
    {
        $this->batchCompletionService->calculateAndStoreProfit($productBatch);

        return back()->with('success', 'Investor shares calculated and updated!');
    }
}
