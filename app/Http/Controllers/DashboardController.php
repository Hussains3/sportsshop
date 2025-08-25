<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        // Get low stock products count (products with total batch quantity < 5)
        $lowStockProduct = Product::where('is_active', true)
            ->whereHas('batches', function ($query) {
                $query->selectRaw('product_id, SUM(current_quantity) as total_stock')
                    ->groupBy('product_id')
                    ->havingRaw('total_stock < 5');
            })->count();

        // Get total active products
        $totalProducts = Product::where('is_active', true)->count();

        // Get out of stock products (no batches or all batches have 0 quantity)
        $outOfStockCount = Product::where('is_active', true)
            ->where(function ($query) {
                $query->whereDoesntHave('batches')
                    ->orWhereHas('batches', function ($q) {
                        $q->selectRaw('product_id, SUM(current_quantity) as total_stock')
                            ->groupBy('product_id')
                            ->havingRaw('total_stock = 0');
                    });
            })->count();

        // Get total categories
        $totalCategories = \App\Models\Category::where('is_active', true)->count();

        // Get total stock value (based on minimum selling price of batches)
        $totalStockValue = Product::where('is_active', true)
            ->withSum('batches', 'current_quantity')
            ->with(['batches' => function ($query) {
                $query->selectRaw('product_id, MIN(min_selling_price) as min_price')
                    ->groupBy('product_id');
            }])
            ->get()
            ->sum(function ($product) {
                $minPrice = $product->batches->first()->min_price ?? 0;
                return ($product->batches_sum_current_quantity ?? 0) * $minPrice;
            });

        // Get featured products count
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->count();

        // Get sales statistics
        $todaySales = Sale::whereDate('created_at', today())->count();
        $todayRevenue = Sale::whereDate('created_at', today())->sum('total_amount');
        $monthlySales = Sale::whereMonth('created_at', now()->month)->count();
        $monthlyRevenue = Sale::whereMonth('created_at', now()->month)->sum('total_amount');
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'lowStockProduct',
            'totalProducts',
            'outOfStockCount',
            'totalCategories',
            'totalStockValue',
            'featuredProducts',
            'todaySales',
            'todayRevenue',
            'monthlySales',
            'monthlyRevenue',
            'recentSales'
        ));
    }

    public function lowStockProducts()
    {
        $lowStockProducts = Product::with(['subcategory', 'category', 'batches'])
            ->where('is_active', true)
            ->withSum('batches', 'current_quantity')
            ->having('batches_sum_current_quantity', '<', 5)
            ->orHaving('batches_sum_current_quantity', null)
            ->get();

        return view('lowStockProducts', compact('lowStockProducts'));
    }
}
