<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;

Route::post('/notifications/{notification}/read', function (DatabaseNotification $notification) {
    abort_unless(
        $notification->notifiable_id === auth()->id()
        && $notification->notifiable_type === auth()->user()::class,
        403
    );

    $notification->markAsRead();

    return back()->with('success', 'Notification marked as read.');
})->middleware('auth')->name('notifications.read');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

   
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/search', [DashboardController::class, 'search'])
        ->name('dashboard.search');

    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])
        ->name('dashboard.refresh');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/suppliers/performance', [
    SupplierController::class,
    'performance'
])->name('suppliers.performance');

    // Inventory Transactions
    Route::get('/inventory', [InventoryTransactionController::class, 'index'])
        ->name('inventory.index');

    Route::get('/inventory/create', [InventoryTransactionController::class, 'create'])
        ->name('inventory.create');

    Route::post('/inventory', [InventoryTransactionController::class, 'store'])
        ->name('inventory.store');
        // Supplier Management
    Route::resource('suppliers', SupplierController::class);

// Purchase Order Management
Route::resource('purchase-orders', PurchaseOrderController::class);

Route::post('/purchase-orders/{purchaseOrder}/submit', [
    PurchaseOrderController::class,
    'submitForApproval'
])->name('purchase-orders.submit');

Route::post('/purchase-orders/{purchaseOrder}/approve', [
    PurchaseOrderController::class,
    'approve'
])->name('purchase-orders.approve');

Route::post('/purchase-orders/{purchaseOrder}/reject', [
    PurchaseOrderController::class,
    'reject'
])->name('purchase-orders.reject');

Route::post('/purchase-orders/{purchaseOrder}/send', [
    PurchaseOrderController::class,
    'markSent'
])->name('purchase-orders.send');

Route::post('/purchase-orders/{purchaseOrder}/receive', [
    PurchaseOrderController::class,
    'receive'
])->name('purchase-orders.receive');
});

require __DIR__.'/auth.php';
