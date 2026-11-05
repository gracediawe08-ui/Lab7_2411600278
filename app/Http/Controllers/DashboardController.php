<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // PRODUCT STATISTICS
        $totalProducts = Product::count();

        $lowStock = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )
        ->where('quantity', '>', 0)
        ->count();

        $outOfStock = Product::where('quantity', 0)->count();

        $inventoryValue = Product::selectRaw(
            'SUM(quantity * unit_price) as total'
        )->value('total') ?? 0;

        $products = Product::latest()->get();

        $recentProducts = Product::latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )
        ->where('quantity', '>', 0)
        ->orderBy('quantity')
        ->get();

        $categoryData = Product::select(
            'category',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * unit_price) as total_value')
        )
        ->groupBy('category')
        ->orderBy('category')
        ->get();

        $inStockCount = Product::whereColumn(
            'quantity',
            '>',
            'reorder_level'
        )->count();

        // PURCHASE ORDER STATISTICS
        $totalPurchaseOrders = PurchaseOrder::count();

        $pendingPurchaseOrders = PurchaseOrder::where(
            'status',
            'pending_approval'
        )->count();

        $approvedPurchaseOrders = PurchaseOrder::where(
            'status',
            'approved'
        )->count();

        $receivedPurchaseOrders = PurchaseOrder::where(
            'status',
            'received'
        )->count();

        $recentPurchaseOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'lowStock',
            'outOfStock',
            'inventoryValue',
            'products',
            'recentProducts',
            'lowStockProducts',
            'categoryData',
            'inStockCount',
            'totalPurchaseOrders',
            'pendingPurchaseOrders',
            'approvedPurchaseOrders',
            'receivedPurchaseOrders',
            'recentPurchaseOrders'
        ));
    }

    public function refresh()
    {
        $totalProducts = Product::count();

        $totalStockQuantity = Product::sum('quantity');

        $lowStock = Product::lowStock()
            ->where('quantity', '>', 0)
            ->count();

        $outOfStock = Product::outOfStock()->count();

        $inStockCount = Product::whereColumn(
            'quantity',
            '>',
            'reorder_level'
        )->count();

        $inventoryValue = Product::selectRaw(
            'COALESCE(SUM(quantity * unit_price), 0) as total'
        )->value('total');

        return response()->json([
            'totalProducts' => $totalProducts,
            'totalStockQuantity' => (int) $totalStockQuantity,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'inStockCount' => $inStockCount,
            'inventoryValue' => (float) $inventoryValue,
            'updatedAt' => now()->toDateTimeString(),
        ]);
    }

    public function search(Request $request)
    {
        $search = trim($request->input('search', ''));
        $category = $request->input('category', '');
        $status = $request->input('status', '');

        $query = Product::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($status === 'in_stock') {
            $query->whereColumn(
                'quantity',
                '>',
                'reorder_level'
            );
        }

        if ($status === 'low_stock') {
            $query->whereColumn(
                'quantity',
                '<=',
                'reorder_level'
            )
            ->where('quantity', '>', 0);
        }

        if ($status === 'out_of_stock') {
            $query->where('quantity', 0);
        }

        $products = $query->latest()->get();

        return response()->json($products);
    }
}
