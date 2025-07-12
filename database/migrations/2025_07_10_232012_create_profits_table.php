<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('product_batch_id')->constrained('product_batches')->onDelete('cascade');
            $table->decimal('total_revenue', 15, 2); // UK Pounds (£)
            $table->decimal('total_profit', 15, 2); // UK Pounds (£)
            $table->date('calculated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profits');
    }
};
