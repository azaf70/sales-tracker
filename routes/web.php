<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Business Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('businesses', BusinessController::class);
    
    // Business Member Management
    Route::post('businesses/{business}/invite-member', [BusinessController::class, 'inviteMember'])
        ->name('businesses.invite-member');
    Route::delete('businesses/{business}/members/{member}', [BusinessController::class, 'removeMember'])
        ->name('businesses.remove-member');
    Route::post('businesses/{business}/calculate-profits', [BusinessController::class, 'calculateProfits'])
        ->name('businesses.calculate-profits');
    
    // Product Batches
    Route::resource('businesses.product-batches', ProductBatchController::class)
        ->shallow();
    
    // Investments
    Route::resource('businesses.investments', InvestmentController::class)
        ->shallow();
    
    // Sales
    Route::resource('businesses.sales', SaleController::class)
        ->shallow();
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
