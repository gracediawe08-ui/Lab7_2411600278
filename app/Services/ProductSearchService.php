<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductSearchService
{
    public function search(
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Product::query()
            ->with('supplierRecord');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where(
                'category',
                $filters['category']
            );
        }

        if (!empty($filters['supplier_id'])) {
            $query->where(
                'supplier_id',
                $filters['supplier_id']
            );
        }

        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'low_stock':
                    $query->whereColumn(
                        'quantity',
                        '<=',
                        'reorder_level'
                    )->where('quantity', '>', 0);
                    break;

                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;

                case 'in_stock':
                    $query->whereColumn(
                        'quantity',
                        '>',
                        'reorder_level'
                    );
                    break;
            }
        }

        $allowedSorts = [
            'name',
            'sku',
            'quantity',
            'unit_price',
            'created_at',
        ];

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        return $query
            ->orderBy($sortBy, $sortDirection)
            ->paginate(max(1, min($perPage, 100)))
            ->withQueryString();
    }
}
