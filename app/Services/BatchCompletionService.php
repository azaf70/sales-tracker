<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductBatch;
use App\Models\Profit;
use Illuminate\Support\Facades\DB;

class BatchCompletionService
{
    public function checkAndCompleteBatch(ProductBatch $batch): bool
    {
        return DB::transaction(function () use ($batch) {
            // Check if batch is already completed
            if ($batch->status === 'completed') {
                return false;
            }

            // Check if all products are sold
            if ($batch->remaining_quantity <= 0) {
                // Mark batch as completed
                $batch->update([
                    'status' => 'completed',
                    'completion_date' => now(),
                ]);

                // Calculate and store profit
                $this->calculateAndStoreProfit($batch);

                return true;
            }

            return false;
        });
    }

    public function calculateAndStoreProfit(ProductBatch $batch): Profit
    {
        $totalRevenue = $batch->total_revenue;
        $totalProfit = $batch->total_profit;

        return $batch->profits()->updateOrCreate(
            ['calculated_at' => now()->toDateString()],
            [
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
            ]
        );
    }

    public function calculateInvestorShares(ProductBatch $batch): array
    {
        $totalInvestment = $batch->investments->sum('amount');
        $totalProfit = $batch->total_profit;

        if ($totalInvestment <= 0) {
            return [];
        }

        return $batch->investments->map(function ($investment) use ($totalInvestment, $totalProfit) {
            $sharePercentage = ($investment->amount / $totalInvestment) * 100;
            $profitShare = ($totalProfit * $sharePercentage) / 100;

            return [
                'user_id' => $investment->user_id,
                'user_name' => $investment->user->name,
                'investment_amount' => $investment->amount,
                'share_percentage' => round($sharePercentage, 2),
                'profit_share' => round($profitShare, 2),
                'invested_at' => $investment->invested_at,
            ];
        })->toArray();
    }

    public function getBatchSummary(ProductBatch $batch): array
    {
        $investorShares = $this->calculateInvestorShares($batch);

        return [
            'batch' => [
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
            ],
            'investors' => $investorShares,
            'total_investment' => $batch->investments->sum('amount'),
            'is_completed' => $batch->is_completed,
        ];
    }
} 