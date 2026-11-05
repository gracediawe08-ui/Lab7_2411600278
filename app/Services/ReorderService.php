<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReorderService
{
    
    private function getUsageMetrics(Product $product): array
    {
        $periodStart = Carbon::now()->subDays(30);

        $totalUsage = InventoryTransaction::query()
            ->where('product_id', $product->id)
            ->where('transaction_type', 'stock_out')
            ->where('created_at', '>=', $periodStart)
            ->sum('quantity');

        $averageDailyUsage = round($totalUsage / 30, 2);

        $leadTimeDays = max(
            1,
            (int) ($product->supplierRecord?->lead_time_days ?? 7)
        );

        $safetyStock = round($averageDailyUsage * 7, 2);

        $reorderPoint = (int) ceil(
            ($averageDailyUsage * $leadTimeDays) + $safetyStock
        );

        return [
            'total_usage' => (int) $totalUsage,
            'average_daily_usage' => $averageDailyUsage,
            'lead_time_days' => $leadTimeDays,
            'safety_stock' => $safetyStock,
            'reorder_point' => $reorderPoint,
        ];
    }

    
    public function calculateReorderPoint(Product $product): int
    {
        return $this->getUsageMetrics($product)['reorder_point'];
    }

    
    public function calculateOptimalOrderQuantity(Product $product): int
    {
        $metrics = $this->getUsageMetrics($product);

        $effectiveReorderPoint = max(
            $metrics['reorder_point'],
            (int) $product->reorder_level
        );

        $targetStock = $effectiveReorderPoint
            + ($metrics['average_daily_usage'] * $metrics['lead_time_days']);

        $requiredQuantity = max(
            0,
            (int) ceil($targetStock - $product->quantity)
        );

        $packageSize = max(1, (int) ($product->package_size ?? 1));

        $minimumOrderQuantity = max(
            1,
            (int) ($product->supplierRecord?->minimum_order_quantity ?? 1)
        );

        $quantityToOrder = max(
            $requiredQuantity,
            $minimumOrderQuantity
        );

        
        return (int) (
            ceil($quantityToOrder / $packageSize) * $packageSize
        );
    }

    
    public function analyze(Product $product): array
    {
        $metrics = $this->getUsageMetrics($product);

        $effectiveReorderPoint = max(
            $metrics['reorder_point'],
            (int) $product->reorder_level
        );

        $needsReorder = $product->quantity <= $effectiveReorderPoint;

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'current_stock' => (int) $product->quantity,
            'reorder_level' => (int) $product->reorder_level,
            'package_size' => (int) ($product->package_size ?? 1),
            'minimum_order_quantity' => (int) (
                $product->supplierRecord?->minimum_order_quantity ?? 1
            ),
            'total_usage_30_days' => $metrics['total_usage'],
            'average_daily_usage' => $metrics['average_daily_usage'],
            'lead_time_days' => $metrics['lead_time_days'],
            'safety_stock' => $metrics['safety_stock'],
            'reorder_point' => $metrics['reorder_point'],
            'effective_reorder_point' => $effectiveReorderPoint,
            'needs_reorder' => $needsReorder,
            'suggested_order_quantity' => $needsReorder
                ? $this->calculateOptimalOrderQuantity($product)
                : 0,
        ];
    }

    public function getProductsNeedingReorder(): Collection
    {
        return Product::with('supplierRecord')
            ->get()
            ->map(function (Product $product) {
                $product->setAttribute(
                    'reorder_analysis',
                    $this->analyze($product)
                );

                return $product;
            })
            ->filter(fn (Product $product) =>
                $product->reorder_analysis['needs_reorder']
            )
            ->values();
    }

    public function groupProductsBySupplier(Collection $products): Collection
    {
        return $products->filter(function (Product $product) {
            return $product->supplier_id !== null
                && $product->supplierRecord !== null;
        })->groupBy('supplier_id');
    }

    public function generatePurchaseOrders(): array
    {
        $products = $this->getProductsNeedingReorder();

        $generatedOrders = [];
        $skippedProducts = [];

       
        $openStatuses = [
            'draft',
            'pending_approval',
            'approved',
            'sent',
            'partially_received',
        ];

        $eligibleProducts = $products->filter(function (Product $product) use (
            $openStatuses,
            &$skippedProducts
        ) {
            $supplier = $product->supplierRecord;

            if (!$supplier || $supplier->status !== 'active') {
                $skippedProducts[] = [
                    'product' => $product->name,
                    'reason' => 'No active supplier assigned.',
                ];

                return false;
            }

            $alreadyOrdered = PurchaseOrderItem::where(
                'product_id',
                $product->id
            )->whereHas('purchaseOrder', function ($query) use ($openStatuses) {
                $query->whereIn('status', $openStatuses);
            })->exists();

            if ($alreadyOrdered) {
                $skippedProducts[] = [
                    'product' => $product->name,
                    'reason' => 'Product already has an open purchase order.',
                ];

                return false;
            }

            return true;
        });

        $productsBySupplier = $this->groupProductsBySupplier(
            $eligibleProducts
        );

        foreach ($productsBySupplier as $supplierId => $supplierProducts) {
            $order = DB::transaction(function () use (
                $supplierId,
                $supplierProducts
            ) {
                $supplier = $supplierProducts->first()->supplierRecord;

                $order = PurchaseOrder::create([
                    'order_number' => 'PO-'
                        . now()->format('Ymd')
                        . '-'
                        . Str::upper(Str::random(6)),
                    'supplier_id' => $supplierId,
                    'created_by' => null,
                    'order_date' => Carbon::today(),
                    'expected_delivery_date' => Carbon::today()->addDays(
                        max(1, (int) $supplier->lead_time_days)
                    ),
                    'status' => 'draft',
                    'subtotal' => 0,
                    'tax_amount' => 0,
                    'shipping_cost' => 0,
                    'total_amount' => 0,
                    'notes' => 'Automatically generated by the inventory replenishment service.',
                ]);

                foreach ($supplierProducts as $product) {
                    $quantity = $product->reorder_analysis[
                        'suggested_order_quantity'
                    ];

                    if ($quantity <= 0) {
                        continue;
                    }

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'received_quantity' => 0,
                        'unit_cost' => $product->unit_price,
                        'line_total' => $quantity * $product->unit_price,
                    ]);
                }

                $order->recalculateTotals();

                return $order->load('supplier', 'items.product');
            });

            $generatedOrders[] = [
                'order_number' => $order->order_number,
                'supplier' => $order->supplier->company_name,
                'items_count' => $order->items->count(),
                'total_amount' => (float) $order->total_amount,
            ];
        }

        return [
            'generated_orders' => $generatedOrders,
            'skipped_products' => $skippedProducts,
            'total_orders' => count($generatedOrders),
            'total_products' => collect($generatedOrders)->sum('items_count'),
        ];
    }

    public function productsNeedingReorder(): Collection
    {
        return $this->getProductsNeedingReorder()
            ->map(fn (Product $product) => $product->reorder_analysis)
            ->values();
    }
}
