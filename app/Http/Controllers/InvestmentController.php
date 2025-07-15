<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Investment\StoreRequest;
use App\Models\Business;
use App\Models\Investment;
use App\Models\ProductBatch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvestmentController extends Controller
{
    public function index(Business $business): Response
    {
        $business->load(['investments.user', 'investments.productBatch']);

        $investments = $business->investments->map(function ($investment) {
            return [
                'id' => $investment->id,
                'user' => $investment->user,
                'product_batch' => $investment->productBatch,
                'amount' => $investment->amount,
                'share_percentage' => $investment->share_percentage,
                'calculated_share_percentage' => round($investment->calculated_share_percentage, 2),
                'profit_share' => round($investment->profit_share, 2),
                'invested_at' => $investment->invested_at,
            ];
        });

        return Inertia::render('Investments/Index', [
            'business' => $business,
            'investments' => $investments,
        ]);
    }

    public function create(Business $business): Response
    {
        $business->load(['productBatches' => function ($query) {
            $query->where('status', 'active');
        }]);

        $users = User::all();

        return Inertia::render('Investments/Create', [
            'business' => $business,
            'users' => $users,
        ]);
    }

    public function store(StoreRequest $request, Business $business): RedirectResponse
    {
        $investment = $business->investments()->create([
            'user_id' => $request->user_id,
            'product_batch_id' => $request->product_batch_id,
            'amount' => $request->amount,
            'share_percentage' => $request->share_percentage ?? 0,
            'invested_at' => $request->invested_at,
        ]);

        // Update share percentages for all investments in this batch
        $this->updateSharePercentages($request->product_batch_id);

        return redirect()->route('businesses.investments.index', $business)
            ->with('success', 'Investment created successfully!');
    }

    public function show(Business $business, Investment $investment): Response
    {
        $investment->load(['user', 'productBatch']);

        return Inertia::render('Investments/Show', [
            'business' => $business,
            'investment' => [
                'id' => $investment->id,
                'user' => $investment->user,
                'product_batch' => $investment->productBatch,
                'amount' => $investment->amount,
                'share_percentage' => $investment->share_percentage,
                'calculated_share_percentage' => round($investment->calculated_share_percentage, 2),
                'profit_share' => round($investment->profit_share, 2),
                'invested_at' => $investment->invested_at,
            ],
        ]);
    }

    public function edit(Business $business, Investment $investment): Response
    {
        $business->load(['productBatches' => function ($query) {
            $query->where('status', 'active');
        }]);

        $users = User::all();

        return Inertia::render('Investments/Edit', [
            'business' => $business,
            'investment' => $investment,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Business $business, Investment $investment): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'amount' => 'required|numeric|min:0',
            'share_percentage' => 'sometimes|numeric|min:0|max:100',
            'invested_at' => 'required|date',
        ]);

        $investment->update([
            'user_id' => $request->user_id,
            'product_batch_id' => $request->product_batch_id,
            'amount' => $request->amount,
            'share_percentage' => $request->share_percentage ?? 0,
            'invested_at' => $request->invested_at,
        ]);

        // Update share percentages for all investments in this batch
        $this->updateSharePercentages($request->product_batch_id);

        return redirect()->route('businesses.investments.index', $business)
            ->with('success', 'Investment updated successfully!');
    }

    public function destroy(Business $business, Investment $investment): RedirectResponse
    {
        $productBatchId = $investment->product_batch_id;
        $investment->delete();

        // Update share percentages for remaining investments in this batch
        if ($productBatchId) {
            $this->updateSharePercentages($productBatchId);
        }

        return redirect()->route('businesses.investments.index', $business)
            ->with('success', 'Investment deleted successfully!');
    }

    private function updateSharePercentages(int $productBatchId): void
    {
        $batch = ProductBatch::find($productBatchId);
        if (!$batch) {
            return;
        }

        $totalInvestment = $batch->investments->sum('amount');
        
        if ($totalInvestment <= 0) {
            return;
        }

        $batch->investments->each(function ($investment) use ($totalInvestment) {
            $sharePercentage = ($investment->amount / $totalInvestment) * 100;
            $investment->update(['share_percentage' => $sharePercentage]);
        });
    }
}
