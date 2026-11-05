<x-app-layout>


<style>
    .product-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .page-header h1 {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
        color: #4a3b47;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #7b6b77;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 18px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-back {
        background: #f3e8f1;
        color: #8e5a83;
    }

    .btn-edit {
        background: linear-gradient(135deg, #8e5a83, #c06c84);
        color: white;
    }

    .btn-delete {
        background: #f8dfe5;
        color: #a63d5d;
    }

    .product-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 10px 35px rgba(92, 63, 83, 0.10);
        border: 1px solid #f1e7ee;
    }

    .product-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding-bottom: 25px;
        border-bottom: 1px solid #f0e8ee;
    }

    .product-title {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .product-icon {
        width: 56px;
        height: 56px;
        border-radius: 15px;
        background: linear-gradient(135deg, #8e5a83, #e5989b);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .product-title h2 {
        margin: 0 0 6px;
        color: #4a3b47;
        font-size: 25px;
    }

    .product-title p {
        margin: 0;
        color: #8a7885;
        font-size: 14px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-good {
        background: #e8f6ee;
        color: #2e7d52;
    }

    .status-low {
        background: #fff2d8;
        color: #a86b00;
    }

    .status-out {
        background: #fde5ea;
        color: #b23b58;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 25px;
    }

    .detail-box {
        background: #fff8fa;
        border: 1px solid #f2e5eb;
        border-radius: 14px;
        padding: 18px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 700;
        color: #917f8c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 7px;
    }

    .detail-value {
        color: #4a3b47;
        font-weight: 600;
        font-size: 16px;
        word-break: break-word;
    }

    .description-box {
        margin-top: 18px;
        background: #fff8fa;
        border: 1px solid #f2e5eb;
        border-radius: 14px;
        padding: 20px;
    }

    .stock-summary {
        margin-top: 25px;
        padding: 22px;
        border-radius: 16px;
        background: linear-gradient(135deg, #f8edf5, #fff1f3);
    }

    .stock-summary h3 {
        margin: 0 0 16px;
        color: #4a3b47;
        font-size: 18px;
    }

    .stock-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .stock-item {
        background: rgba(255, 255, 255, 0.8);
        padding: 16px;
        border-radius: 12px;
        text-align: center;
    }

    .stock-number {
        font-size: 25px;
        font-weight: 800;
        color: #8e5a83;
    }

    .stock-label {
        font-size: 12px;
        color: #7c6d77;
        margin-top: 4px;
    }

    .action-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f0e8ee;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .product-page {
            padding: 20px;
        }

        .product-top {
            flex-direction: column;
        }

        .details-grid,
        .stock-grid {
            grid-template-columns: 1fr;
        }

        .header-actions,
        .action-footer {
            width: 100%;
        }

        .header-actions .btn,
        .action-footer .btn {
            flex: 1;
        }
    }
</style>

@php
    $categoryIcon = match ($product->category) {
        'Lumber' => '🪵',
        'Hardware' => '🔩',
        'Tools' => '🔧',
        'Electrical' => '⚡',
        'Plumbing' => '🚰',
        'Paint' => '🎨',
        default => '📦',
    };

    if ($product->quantity == 0) {
        $statusClass = 'status-out';
        $statusText = 'Out of Stock';
    } elseif ($product->quantity <= $product->reorder_level) {
        $statusClass = 'status-low';
        $statusText = 'Low Stock';
    } else {
        $statusClass = 'status-good';
        $statusText = 'In Stock';
    }
@endphp

<div class="product-page">

    <div class="page-header">
        <div>
            <h1>Product Details</h1>
            <p>View complete information about this hardware product.</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('products.index') }}" class="btn btn-back">
                ← Back to Products
            </a>

            <a href="{{ route('products.edit', $product) }}" class="btn btn-edit">
                ✎ Edit Product
            </a>
        </div>
    </div>

    <div class="product-card">

        <div class="product-top">
            <div class="product-title">

                <div class="product-icon">
                    {{ $categoryIcon }}
                </div>

                <div>
                    <h2>{{ $product->name }}</h2>
                    <p>SKU: {{ $product->sku }}</p>
                </div>

            </div>

            <span class="status-badge {{ $statusClass }}">
                ● {{ $statusText }}
            </span>
        </div>

        <div class="details-grid">

            <div class="detail-box">
                <div class="detail-label">Product Name</div>
                <div class="detail-value">
                    {{ $product->name }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-label">SKU</div>
                <div class="detail-value">
                    {{ $product->sku }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-label">Category</div>
                <div class="detail-value">
                    {{ $product->category }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-label">Supplier</div>
                <div class="detail-value">
                    {{ $product->supplier ?: 'No supplier specified' }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-label">Unit Price</div>
                <div class="detail-value">
                    ₱{{ number_format($product->unit_price, 2) }}
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-label">Reorder Level</div>
                <div class="detail-value">
                    {{ $product->reorder_level }}
                </div>
            </div>

        </div>

        <div class="description-box">
            <div class="detail-label">Description</div>

            <div class="detail-value">
                {{ $product->description ?: 'No description provided.' }}
            </div>
        </div>

        <div class="stock-summary">
            <h3>Inventory Summary</h3>

            <div class="stock-grid">

                <div class="stock-item">
                    <div class="stock-number">
                        {{ $product->quantity }}
                    </div>

                    <div class="stock-label">
                        Current Quantity
                    </div>
                </div>

                <div class="stock-item">
                    <div class="stock-number">
                        {{ $product->reorder_level }}
                    </div>

                    <div class="stock-label">
                        Reorder Level
                    </div>
                </div>

                <div class="stock-item">
                    <div class="stock-number">
                        ₱{{ number_format($product->quantity * $product->unit_price, 2) }}
                    </div>

                    <div class="stock-label">
                        Inventory Value
                    </div>
                </div>

            </div>
        </div>

        <div class="action-footer">

            <a href="{{ route('products.index') }}" class="btn btn-back">
                ← Back
            </a>

            <a href="{{ route('products.edit', $product) }}" class="btn btn-edit">
                ✎ Edit
            </a>

            <form
                action="{{ route('products.destroy', $product) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this product?');"
                style="display: inline;"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-delete">
                    🗑 Delete
                </button>
            </form>

        </div>

    </div>

</div>


</x-app-layout>
