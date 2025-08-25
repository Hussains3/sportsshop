<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\SaleItem;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            // Default to current month
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        // Summary statistics
        $summary = [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'total_items_sold' => $query->withSum('items', 'quantity')->get()->sum('items_sum_quantity'),
            'average_sale' => $query->count() > 0 ? $query->sum('total_amount') / $query->count() : 0,
        ];

        // Payment method breakdown
        $paymentMethods = $query->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Daily sales trend
        $dailySales = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as sales_count'),
            DB::raw('sum(total_amount) as revenue')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('reports.sales', compact('sales', 'summary', 'paymentMethods', 'dailySales'));
    }

    public function inventory(Request $request)
    {
        $query = Product::with(['subcategory.category', 'batches'])
            ->where('is_active', true)
            ->withSum('batches', 'current_quantity');

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
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

        $products = $query->orderBy('name')->paginate(20);

        // Summary statistics
        $summary = [
            'total_products' => Product::where('is_active', true)->count(),
            'total_stock_value' => $this->calculateTotalStockValue(),
            'low_stock_count' => Product::where('is_active', true)
                ->withSum('batches', 'current_quantity')
                ->having('batches_sum_current_quantity', '<', 5)
                ->orHaving('batches_sum_current_quantity', null)
                ->count(),
            'out_of_stock_count' => Product::where('is_active', true)
                ->withSum('batches', 'current_quantity')
                ->having('batches_sum_current_quantity', '=', 0)
                ->orHaving('batches_sum_current_quantity', null)
                ->count(),
        ];

        // Category breakdown
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])->get();

        return view('reports.inventory', compact('products', 'summary', 'categories'));
    }

    public function topProducts(Request $request)
    {
        $query = SaleItem::with(['product.subcategory.category'])
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT sale_id) as sale_count')
            )
            ->groupBy('product_id');

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereHas('sale', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        } else {
            // Default to current month
            $query->whereHas('sale', function($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            });
        }

        $topProducts = $query->orderBy('total_quantity', 'desc')
            ->with('product')
            ->take(20)
            ->get();

        return view('reports.top-products', compact('topProducts'));
    }

    public function financial(Request $request)
    {
        $query = Sale::query();

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            // Default to current month
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        // Summary statistics
        $summary = [
            'total_revenue' => $query->sum('total_amount'),
            'total_tax' => $query->sum('tax_amount'),
            'total_discount' => $query->sum('discount_amount'),
            'net_revenue' => $query->sum('total_amount') - $query->sum('discount_amount'),
            'total_sales' => $query->count(),
            'average_sale' => $query->count() > 0 ? $query->sum('total_amount') / $query->count() : 0,
        ];

        // Monthly trend
        $monthlyTrend = Sale::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as revenue'),
            DB::raw('COUNT(*) as sales_count'),
            DB::raw('SUM(tax_amount) as tax'),
            DB::raw('SUM(discount_amount) as discount')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        // Payment method breakdown
        $paymentMethods = $query->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('reports.financial', compact('summary', 'monthlyTrend', 'paymentMethods'));
    }

    public function exportSales(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'sales_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Sale Number',
                'Date',
                'Cashier',
                'Items Count',
                'Subtotal',
                'Tax',
                'Discount',
                'Total',
                'Payment Method',
                'Status'
            ]);

            // CSV data
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->sale_number,
                    $sale->created_at->format('Y-m-d H:i:s'),
                    $sale->user->name,
                    $sale->items->count(),
                    $sale->subtotal,
                    $sale->tax_amount,
                    $sale->discount_amount,
                    $sale->total_amount,
                    $sale->payment_method,
                    $sale->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calculateTotalStockValue()
    {
        return Product::where('is_active', true)
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
    }
}
