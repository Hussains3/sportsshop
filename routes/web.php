<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:view-dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware('permission:edit-profile')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('permission:edit-profile')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('permission:delete-profile')->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // Category Routes with Permissions
    Route::get('categories', [CategoryController::class, 'index'])->middleware('permission:view-categories')->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->middleware('permission:create-categories')->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->middleware('permission:create-categories')->name('categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->middleware('permission:view-categories')->name('categories.show');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('permission:edit-categories')->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('permission:edit-categories')->name('categories.update');
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->middleware('permission:edit-categories');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete-categories')->name('categories.destroy');

    // SubCategory Routes with Permissions
    Route::get('subcategories', [SubCategoryController::class, 'index'])->middleware('permission:view-subcategories')->name('subcategories.index');
    Route::get('subcategories/create', [SubCategoryController::class, 'create'])->middleware('permission:create-subcategories')->name('subcategories.create');
    Route::post('subcategories', [SubCategoryController::class, 'store'])->middleware('permission:create-subcategories')->name('subcategories.store');
    Route::get('subcategories/{subcategory}', [SubCategoryController::class, 'show'])->middleware('permission:view-subcategories')->name('subcategories.show');
    Route::get('subcategories/{subcategory}/edit', [SubCategoryController::class, 'edit'])->middleware('permission:edit-subcategories')->name('subcategories.edit');
    Route::put('subcategories/{subcategory}', [SubCategoryController::class, 'update'])->middleware('permission:edit-subcategories')->name('subcategories.update');
    Route::patch('subcategories/{subcategory}', [SubCategoryController::class, 'update'])->middleware('permission:edit-subcategories');
    Route::delete('subcategories/{subcategory}', [SubCategoryController::class, 'destroy'])->middleware('permission:delete-subcategories')->name('subcategories.destroy');

    // Product Routes with Permissions
    Route::get('products', [ProductController::class, 'index'])->middleware('permission:view-products')->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->middleware('permission:create-products')->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->middleware('permission:create-products')->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->middleware('permission:view-products')->name('products.show');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->middleware('permission:edit-products')->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->middleware('permission:edit-products')->name('products.update');
    Route::patch('products/{product}', [ProductController::class, 'update'])->middleware('permission:edit-products');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware('permission:delete-products')->name('products.destroy');

    // Batch Routes with Permissions
    Route::get('batches', [BatchController::class, 'index'])->middleware('permission:view-batches')->name('batches.index');
    Route::get('batches/create', [BatchController::class, 'create'])->middleware('permission:create-batches')->name('batches.create');
    Route::post('batches', [BatchController::class, 'store'])->middleware('permission:create-batches')->name('batches.store');
    Route::get('batches/{batch}', [BatchController::class, 'show'])->middleware('permission:view-batches')->name('batches.show');
    Route::get('batches/{batch}/edit', [BatchController::class, 'edit'])->middleware('permission:edit-batches')->name('batches.edit');
    Route::put('batches/{batch}', [BatchController::class, 'update'])->middleware('permission:edit-batches')->name('batches.update');
    Route::patch('batches/{batch}', [BatchController::class, 'update'])->middleware('permission:edit-batches');
    Route::delete('batches/{batch}', [BatchController::class, 'destroy'])->middleware('permission:delete-batches')->name('batches.destroy');

    Route::get('lowStockProducts', [DashboardController::class, 'lowStockProducts'])->middleware('permission:view-low-stock')->name('lowStockProducts');

    // POS Routes with Permissions
    Route::get('pos', [POSController::class, 'index'])->middleware('permission:access-pos')->name('pos.index');
    Route::post('pos/search', [POSController::class, 'searchProducts'])->middleware('permission:access-pos')->name('pos.search');
    Route::post('pos/batches', [POSController::class, 'getProductBatches'])->middleware('permission:access-pos')->name('pos.batches');
    Route::post('pos/sale', [POSController::class, 'store'])->middleware('permission:create-sales')->name('pos.store');
    Route::get('pos/receipt/{id}', [POSController::class, 'receipt'])->middleware('permission:view-receipts')->name('pos.receipt');
    Route::get('pos/sales', [POSController::class, 'sales'])->middleware('permission:view-sales')->name('pos.sales');
    Route::get('pos/sale/{id}', [POSController::class, 'show'])->middleware('permission:view-sales')->name('pos.show');

    // Report Routes with Permissions
    Route::get('reports', [ReportController::class, 'index'])->middleware('permission:view-reports')->name('reports.index');
    Route::get('reports/sales', [ReportController::class, 'sales'])->middleware('permission:view-sales-reports')->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->middleware('permission:view-inventory-reports')->name('reports.inventory');
    Route::get('reports/top-products', [ReportController::class, 'topProducts'])->middleware('permission:view-reports')->name('reports.top-products');
    Route::get('reports/financial', [ReportController::class, 'financial'])->middleware('permission:view-financial-reports')->name('reports.financial');
    Route::get('reports/export-sales', [ReportController::class, 'exportSales'])->middleware('permission:export-reports')->name('reports.export-sales');
    
    // Admin Routes for User Management
    Route::resource('users', UserManagementController::class);
    
    // Admin Routes for Role and Permission Management
    Route::get('admin/roles', [RolePermissionController::class, 'roles'])->name('admin.roles');
    Route::get('admin/roles/create', [RolePermissionController::class, 'createRole'])->name('admin.roles.create');
    Route::post('admin/roles', [RolePermissionController::class, 'storeRole'])->name('admin.roles.store');
    Route::get('admin/roles/{role}/edit', [RolePermissionController::class, 'editRole'])->name('admin.roles.edit');
    Route::put('admin/roles/{role}', [RolePermissionController::class, 'updateRole'])->name('admin.roles.update');
    Route::delete('admin/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('admin.roles.destroy');
    
    Route::get('admin/permissions', [RolePermissionController::class, 'permissions'])->name('admin.permissions');
    Route::get('admin/permissions/create', [RolePermissionController::class, 'createPermission'])->name('admin.permissions.create');
    Route::post('admin/permissions', [RolePermissionController::class, 'storePermission'])->name('admin.permissions.store');
    Route::delete('admin/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('admin.permissions.destroy');
});

require __DIR__.'/auth.php';
