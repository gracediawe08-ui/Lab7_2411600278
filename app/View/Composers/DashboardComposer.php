<?php

namespace App\View\Composers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\ReorderService;
use Illuminate\View\View;

class DashboardComposer
{
    public function __construct(
        protected ReorderService $reorderService
    ) {
    }

    public function compose(View $view): void
    {
        $pendingOrders = PurchaseOrder::with('supplier')
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->take(5)
            ->get();

        $suppliers = Supplier::withCount('purchaseOrders')
            ->withSum('purchaseOrders', 'total_amount')
            ->orderByDesc('purchase_orders_count')
            ->take(5)
            ->get();

        $reorderAlerts = $this->reorderService
            ->getProductsNeedingReorder()
            ->take(8);

        $view->with([
            'widgetPendingOrders' => $pendingOrders,
            'widgetRecentOrders' => $recentOrders,
            'widgetSuppliers' => $suppliers,
            'widgetReorderAlerts' => $reorderAlerts,
        ]);
    }
}