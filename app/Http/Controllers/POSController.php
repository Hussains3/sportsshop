<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with(['batches' => function($query) {
            $query->where('current_quantity', '>', 0);
        }, 'subcategory.category'])
        ->where('is_active', true)
        ->get();

        $categories = \App\Models\Category::with('subcategories')->get();

        return view('pos.index', compact('products', 'categories'));
    }

        public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        $categoryId = $request->get('category_id');

        $products = Product::with(['batches' => function($query) {
            $query->where('current_quantity', '>', 0);
        }, 'subcategory.category'])
        ->where('is_active', true);

        // Apply search filter
        if (!empty($query)) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            });
        }

        // Apply category filter
        if (!empty($categoryId)) {
            $products->whereHas('subcategory', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $products = $products->get();

        return response()->json($products);
    }

    public function getProductBatches(Request $request)
    {
        $productId = $request->get('product_id');

        $batches = Batch::with('product')
            ->where('product_id', $productId)
            ->where('current_quantity', '>', 0)
            ->orderBy('min_selling_price')
            ->get();

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.batch_id' => 'required|exists:batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount,
                'discount_amount' => $request->discount_amount,
                'total_amount' => $request->total_amount,
                'amount_paid' => $request->amount_paid,
                'change_amount' => $request->amount_paid - $request->total_amount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes
            ]);

            // Create sale items and update batch quantities
            foreach ($request->items as $item) {
                $batch = Batch::findOrFail($item['batch_id']);

                if ($batch->current_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: " . $batch->product->name);
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'subtotal' => ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0)
                ]);

                // Update batch quantity
                $batch->decrement('current_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'sale_number' => $sale->sale_number,
                'message' => 'Sale completed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function receipt($id)
    {
        $sale = Sale::with(['items.product', 'items.batch', 'user'])->findOrFail($id);
        return view('pos.receipt', compact('sale'));
    }

    public function sales()
    {
        $sales = Sale::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pos.sales', compact('sales'));
    }

    public function show($id)
    {
        $sale = Sale::with(['items.product', 'items.batch', 'user'])->findOrFail($id);
        return view('pos.show', compact('sale'));
    }
}
