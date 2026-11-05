<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Services\ProductSearchService;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'payment_terms',
        'lead_time_days',
        'minimum_order_amount',
        'minimum_order_quantity',
        'status',
    ];

    protected $casts = [
        'lead_time_days' => 'integer',
        'minimum_order_amount' => 'decimal:2',
        'minimum_order_quantity' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function activityLogs(): MorphMany
    {
    return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeWithLeadTime(
        Builder $query,
        int $days
    ): Builder {
        return $query->where('lead_time_days', '<=', $days);
    }

    public function getFullContactAttribute(): string
    {
        return $this->contact_person . ' - ' .
            ($this->phone ?? 'No phone number');
    }

    public function getPerformanceRatingAttribute(): string
    {
        return 'Not Rated';
    }
}
