<?php

namespace App\Providers;

use App\Events\LowStockDetected;
use App\Listeners\SendLowStockAlert;
use App\Models\InventoryTransaction;
use App\Observers\InventoryTransactionObserver;
use App\Policies\PurchaseOrderPolicy;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\DashboardComposer;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        
        Gate::policy(PurchaseOrder::class, PurchaseOrderPolicy::class);

        
        InventoryTransaction::observe(InventoryTransactionObserver::class);

        
        Event::listen(
            LowStockDetected::class,
            SendLowStockAlert::class
        );
        View::composer('dashboard', DashboardComposer::class);
    }
}
