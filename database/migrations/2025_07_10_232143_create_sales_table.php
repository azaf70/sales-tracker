<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('product_batch_id')->constrained('product_batches')->onDelete('cascade');
            $table->foreignId('sold_by')->nullable()->constrained('users');
            $table->integer('quantity');
            $table->decimal('sale_price', 15, 2); // UK Pounds (£) - total price for this sale
            $table->dateTime('sold_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
}; 