<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'product_batch_id',
        'total_revenue',
        'total_profit',
        'calculated_at',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2', // UK Pounds (£)
        'total_profit' => 'decimal:2', // UK Pounds (£)
        'calculated_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function productBatch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class);
    }
}
