<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('batches', BatchController::class);
    Route::get('lowStockProducts', [DashboardController::class, 'lowStockProducts'])->name('lowStockProducts');

    // POS Routes
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos/search', [POSController::class, 'searchProducts'])->name('pos.search');
    Route::post('pos/batches', [POSController::class, 'getProductBatches'])->name('pos.batches');
    Route::post('pos/sale', [POSController::class, 'store'])->name('pos.store');
    Route::get('pos/receipt/{id}', [POSController::class, 'receipt'])->name('pos.receipt');
    Route::get('pos/sales', [POSController::class, 'sales'])->name('pos.sales');
    Route::get('pos/sale/{id}', [POSController::class, 'show'])->name('pos.show');

    // Report Routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/top-products', [ReportController::class, 'topProducts'])->name('reports.top-products');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/export-sales', [ReportController::class, 'exportSales'])->name('reports.export-sales');
});

require __DIR__.'/auth.php';
