<?php

namespace Tests\Feature;

use App\Events\LowStockDetected;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LowStockAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_event_sends_notification_to_administrator(): void
    {
        Notification::fake();

        $administrator = User::factory()->create();

        Event::dispatch(
            new LowStockDetected(
                45,
                'Exterior Paint Gray 1L',
                'PNT-002',
                4,
                5
            )
        );

        Notification::assertSentTo(
            $administrator,
            LowStockNotification::class
        );
    }

    public function test_stock_out_transaction_dispatches_low_stock_event(): void
    {
        Event::fake([LowStockDetected::class]);

        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Test Low Stock Product',
            'sku' => 'TEST-LOW-001',
            'description' => 'Temporary product for automated testing',
            'category' => 'Hardware',
            'quantity' => 6,
            'reorder_level' => 5,
            'unit_price' => 100,
            'supplier' => 'Test Supplier',
        ]);

        InventoryTransaction::create([
            'product_id' => $product->id,
            'transaction_type' => 'stock_out',
            'quantity' => 2,
            'reference_document' => 'TEST-LOW-OUT-001',
            'user_id' => $user->id,
            'notes' => 'Automated low-stock test',
        ]);

        Event::assertDispatched(
            LowStockDetected::class,
            function (LowStockDetected $event) use ($product) {
                return $event->productId === $product->id
                    && $event->quantity === 4
                    && $event->reorderLevel === 5;
            }
        );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 4,
        ]);
    }
}
