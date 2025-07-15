<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'product_batch_id',
        'amount',
        'share_percentage',
        'invested_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2', // UK Pounds (£)
        'share_percentage' => 'decimal:2',
        'invested_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productBatch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class);
    }

    public function getProfitShareAttribute(): float
    {
        if (!$this->productBatch) {
            return 0;
        }

        $totalProfit = $this->productBatch->total_profit;
        return ($totalProfit * $this->share_percentage) / 100;
    }

    public function getCalculatedSharePercentageAttribute(): float
    {
        if (!$this->productBatch) {
            return 0;
        }

        $totalInvestment = $this->productBatch->investments->sum('amount');
        if ($totalInvestment <= 0) {
            return 0;
        }

        return ($this->amount / $totalInvestment) * 100;
    }
}
