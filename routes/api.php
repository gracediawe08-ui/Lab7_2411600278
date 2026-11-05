
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardApiController;

Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);
Route::get('/products/low-stock', [DashboardApiController::class, 'lowStock']);
Route::get('/transactions/recent', [DashboardApiController::class, 'recentTransactions']);
