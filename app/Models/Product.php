<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'quantity',
        'reorder_level',
        'package_size',
        'unit_price',
        'supplier',
        'supplier_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reorder_level' => 'integer',
        'package_size' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function supplierRecord(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function latestTransaction(): HasOne
    {
        return $this->hasOne(InventoryTransaction::class)
            ->latestOfMany();
    }

    public function purchaseOrders(): BelongsToMany
{
    return $this->belongsToMany(
        PurchaseOrder::class,
        'purchase_order_items',
        'product_id',
        'purchase_order_id'
    )->withPivot([
        'quantity',
        'received_quantity',
        'unit_cost',
        'line_total',
        'notes',
    ])->withTimestamps();
}

public function activityLogs(): MorphMany
{
    return $this->morphMany(ActivityLog::class, 'loggable');
}

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        }

        if ($this->isLowStock()) {
            return 'warning';
        }

        return 'success';
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        );
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeInCategory(
        Builder $query,
        string $category
    ): Builder {
        return $query->where('category', $category);
    }
}
