<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'transaction_type',
        'quantity',
        'reference_document',
        'user_id',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStockIn(Builder $query): Builder
    {
        return $query->where('transaction_type', 'stock_in');
    }

    public function scopeStockOut(Builder $query): Builder
    {
        return $query->where('transaction_type', 'stock_out');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }
}
