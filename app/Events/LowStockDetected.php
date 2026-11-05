<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $productId,
        public string $productName,
        public string $sku,
        public int $quantity,
        public int $reorderLevel
    ) {
    }
}
