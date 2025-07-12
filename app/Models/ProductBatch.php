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
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_cost' => 'decimal:2', // UK Pounds (£)
        'total_quantity' => 'integer',
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
}
