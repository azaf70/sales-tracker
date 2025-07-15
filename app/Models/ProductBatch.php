<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'purchase_date',
        'total_cost',
        'total_quantity',
        'status',
        'completion_date',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_cost' => 'decimal:2', // UK Pounds (£)
        'total_quantity' => 'integer',
        'completion_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function profits(): HasMany
    {
        return $this->hasMany(Profit::class);
    }

    public function getRemainingQuantityAttribute(): int
    {
        $soldQuantity = $this->sales()->sum('quantity');
        return $this->total_quantity - $soldQuantity;
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->sales()->sum('sale_price');
    }

    public function getTotalProfitAttribute(): float
    {
        return $this->total_revenue - $this->total_cost;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getSoldPercentageAttribute(): float
    {
        if ($this->total_quantity === 0) {
            return 0;
        }
        return (($this->total_quantity - $this->remaining_quantity) / $this->total_quantity) * 100;
    }

    public function checkAndMarkAsCompleted(): bool
    {
        if ($this->remaining_quantity <= 0 && $this->status === 'active') {
            $this->update([
                'status' => 'completed',
                'completion_date' => now(),
            ]);
            return true;
        }
        return false;
    }

    public function calculateInvestorShares(): array
    {
        $totalInvestment = $this->investments->sum('amount');
        $totalProfit = $this->total_profit;
        
        if ($totalInvestment <= 0) {
            return [];
        }

        return $this->investments->map(function ($investment) use ($totalInvestment, $totalProfit) {
            $sharePercentage = ($investment->amount / $totalInvestment) * 100;
            $profitShare = ($totalProfit * $sharePercentage) / 100;
            
            return [
                'user' => $investment->user,
                'investment_amount' => $investment->amount,
                'share_percentage' => $sharePercentage,
                'profit_share' => $profitShare,
                'invested_at' => $investment->invested_at,
            ];
        })->toArray();
    }
}
