<?php

namespace App\Observers;

use App\Events\LowStockDetected;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryTransactionObserver
{
    public function creating(InventoryTransaction $transaction): void
    {
        $quantity = (int) $transaction->quantity;

        if ($quantity <= 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be greater than zero.',
            ]);
        }

        if (!in_array($transaction->transaction_type, ['stock_in', 'stock_out'], true)) {
            throw ValidationException::withMessages([
                'transaction_type' => 'Invalid transaction type.',
            ]);
        }

        $product = Product::whereKey($transaction->product_id)
            ->lockForUpdate()
            ->firstOrFail();

        if (
            $transaction->transaction_type === 'stock_out'
            && $product->quantity < $quantity
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock. Available stock: '
                    . $product->quantity,
            ]);
        }
    }

    public function created(InventoryTransaction $transaction): void
    {
        $product = Product::whereKey($transaction->product_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($transaction->transaction_type === 'stock_in') {
            $product->increment('quantity', $transaction->quantity);
        } elseif ($transaction->transaction_type === 'stock_out') {
            $product->decrement('quantity', $transaction->quantity);
        }

        $productId = (int) $product->id;

        DB::afterCommit(function () use ($productId) {
            $product = Product::find($productId);

            if (
                !$product
                || $product->quantity <= 0
                || $product->quantity > $product->reorder_level
            ) {
                return;
            }

            LowStockDetected::dispatch(
                (int) $product->id,
                (string) $product->name,
                (string) $product->sku,
                (int) $product->quantity,
                (int) $product->reorder_level
            );
        });
    }
}
