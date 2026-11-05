<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_products' => Product::count(),
            'total_stock_quantity' => Product::sum('quantity'),
            'inventory_value' => Product::query()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
                ->value('total'),
            'low_stock_items' => Product::whereColumn('quantity', '<=', 'reorder_level')
                ->where('quantity', '>', 0)
                ->count(),
            'out_of_stock_items' => Product::where('quantity', '<=', 0)->count(),
            'last_updated' => now()->toDateTimeString(),
        ]);
    }

    public function lowStock(): JsonResponse
    {
        return response()->json([
            'data' => Product::whereColumn('quantity', '<=', 'reorder_level')
                ->where('quantity', '>', 0)
                ->orderBy('quantity')
                ->get(),
        ]);
    }

    public function recentTransactions(): JsonResponse
    {
        return response()->json([
            'data' => InventoryTransaction::with(['product', 'user'])
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}