<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['subcategory', 'category']);

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by subcategory
        if ($request->filled('subcategory_id')) {
            $query->where('sub_category_id', $request->subcategory_id);
        }

        // Stock Status Filter
        if ($request->filled('stock')) {
            $query->withSum('batches', 'current_quantity');

            switch ($request->stock) {
                case 'in_stock':
                    $query->having('batches_sum_current_quantity', '>=', 5);
                    break;
                case 'low_stock':
                    $query->having('batches_sum_current_quantity', '>', 0)
                          ->having('batches_sum_current_quantity', '<', 5);
                    break;
                case 'out_of_stock':
                    $query->having('batches_sum_current_quantity', '=', 0)
                          ->orHaving('batches_sum_current_quantity', null);
                    break;
            }
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        if ($request->filled('sort')) {
            $sortField = 'created_at';
            $sortOrder = 'desc';

            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->withMin('batches', 'min_selling_price')
                         ->orderBy('batches_min_min_selling_price', 'asc');
                    break;
                case 'price_desc':
                    $query->withMin('batches', 'min_selling_price')
                         ->orderBy('batches_min_min_selling_price', 'desc');
                    break;
                case 'stock_asc':
                    $query->withSum('batches', 'current_quantity')
                         ->orderBy('batches_sum_current_quantity', 'asc');
                    break;
                case 'stock_desc':
                    $query->withSum('batches', 'current_quantity')
                         ->orderBy('batches_sum_current_quantity', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // Get the results with pagination
        $products = $query->paginate(10)->withQueryString();

        // Get categories and subcategories for filter dropdowns
        $categories = Category::where('is_active', true)->get();
        $subcategories = SubCategory::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'subcategories'));

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by stock level
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<', 5);
                    break;
                case 'in_stock':
                    $query->where('stock', '>=', 5);
                    break;
            }
        }

        // Sort by price
        if ($request->filled('sort_price')) {
            $query->orderBy('price', $request->sort_price);
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = \App\Models\Category::where('is_active', true)->get();
        $subcategories = \App\Models\SubCategory::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'subcategories'));
    }

    public function create()
    {
        $subcategories = SubCategory::with('category')
            ->where('is_active', true)
            ->whereHas('category', function($query) {
                $query->where('is_active', true);
            })
            ->get();
        return view('products.create', compact('subcategories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        // Load product with relationships and calculate profit data
        $product->load(['batches' => function($query) {
            $query->orderBy('purchase_date', 'desc');
        }, 'category', 'subcategory']);

        // Calculate profit statistics
        $totalStockValue = $product->batches->sum(function($batch) {
            return $batch->current_quantity * $batch->purchase_price;
        });

        $totalPotentialRevenue = $product->batches->sum(function($batch) {
            return $batch->current_quantity * $batch->max_selling_price;
        });

        $totalMinRevenue = $product->batches->sum(function($batch) {
            return $batch->current_quantity * $batch->min_selling_price;
        });

        $totalPotentialProfit = $totalPotentialRevenue - $totalStockValue;
        $totalMinProfit = $totalMinRevenue - $totalStockValue;

        $averageProfitMargin = $product->batches->avg('profit_margin');
        $averageMaxProfitMargin = $product->batches->avg('max_profit_margin');

        return view('products.show', compact(
            'product',
            'totalStockValue',
            'totalPotentialRevenue',
            'totalMinRevenue',
            'totalPotentialProfit',
            'totalMinProfit',
            'averageProfitMargin',
            'averageMaxProfitMargin'
        ));
    }

    public function edit(Product $product)
    {
        // Load all active subcategories (including product's subcategory even if inactive)
        $subcategories = SubCategory::with('category')
            ->where(function($query) use ($product) {
                $query->where('is_active', true)
                    ->orWhere('id', $product->sub_category_id);
            })
            ->whereHas('category', function($query) use ($product) {
                $query->where('is_active', true)
                    ->orWhereHas('subcategories', function($q) use ($product) {
                        $q->where('id', $product->sub_category_id);
                    });
            })
            ->get();

        return view('products.edit', compact('product', 'subcategories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
