<?php

namespace App\Console\Commands;

use App\Services\ReorderService;
use Illuminate\Console\Command;

class CheckInventoryReorder extends Command
{
    protected $signature = 'inventory:check-reorder';

    protected $description = 'Check inventory products that need replenishment';

    public function handle(ReorderService $reorderService): int
    {
        $products = $reorderService->productsNeedingReorder();

        $this->info('AUTOMATED INVENTORY REPLENISHMENT REPORT');
        $this->line('Date: ' . now()->format('F d, Y h:i A'));
        $this->newLine();

        if ($products->isEmpty()) {
            $this->info('No products currently need replenishment.');
            return self::SUCCESS;
        }

        $rows = $products->map(function (array $product) {
            return [
                $product['sku'],
                $product['product_name'],
                $product['current_stock'],
                $product['effective_reorder_point'],
                $product['lead_time_days'],
                $product['suggested_order_quantity'],
            ];
        })->toArray();

        $this->table(
            [
                'SKU',
                'Product',
                'Current Stock',
                'Reorder Point',
                'Lead Time',
                'Suggested Order',
            ],
            $rows
        );

        $this->warn(
            $products->count() . ' product(s) need replenishment.'
        );

        return self::SUCCESS;
    }
}
