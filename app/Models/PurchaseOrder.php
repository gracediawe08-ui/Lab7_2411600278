<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'order_number',
        'supplier_id',
        'created_by',
        'approved_by',
        'order_date',
        'expected_delivery_date',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'approved_at',
        'sent_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function activityLogs(): MorphMany
    {
    return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function products(): BelongsToMany
    {
    return $this->belongsToMany(
        Product::class,
        'purchase_order_items',
        'purchase_order_id',
        'product_id'
    )->withPivot([
        'quantity',
        'received_quantity',
        'unit_cost',
        'line_total',
        'notes',
    ])->withTimestamps();
}

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeForSupplier(Builder $query, int $supplierId): Builder
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function recalculateTotals(): void
    {
        $subtotal = $this->items()->sum('line_total');

        $this->subtotal = $subtotal;
        $this->total_amount = $subtotal
            + $this->tax_amount
            + $this->shipping_cost;

        $this->save();
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function canBeSent(): bool
    {
        return $this->status === 'approved';
    }

    public function canBeReceived(): bool
    {
        return in_array($this->status, ['sent', 'partially_received']);
    }
}
