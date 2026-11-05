<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlert
{
    public function handle(LowStockDetected $event): void
    {
        $product = Product::with('supplierRecord')
            ->find($event->productId);

        if (!$product) {
            return;
        }

        $administrators = User::where('role', 'admin')
            ->whereNotNull('email')
            ->get();

        foreach ($administrators as $administrator) {

            // Send in-app database notification
            $administrator->notify(
                new LowStockNotification(
                    $event->productId,
                    $event->productName,
                    $event->sku,
                    $event->quantity,
                    $event->reorderLevel
                )
            );

            // Send email notification
            Mail::to($administrator->email)->send(
                new LowStockAlert($product)
            );
        }
    }
}
