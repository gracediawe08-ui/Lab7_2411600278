<x-app-layout>


<style>
    .products-page {
        max-width: 1250px;
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

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8e5a83, #c06c84);
        color: white;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 7px 18px rgba(142, 90, 131, 0.22);
    }

    .btn-add:hover {
        transform: translateY(-1px);
    }

    .alert-success {
        background: #e9f7ef;
        color: #2e7d52;
        border: 1px solid #cbead7;
        border-radius: 12px;
        padding: 13px 16px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .products-card {
        background: #ffffff;
        border: 1px solid #f0e4eb;
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(92, 63, 83, 0.08);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1050px;
    }

    .products-table th {
        background: #fff5f8;
        color: #755f6d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        text-align: left;
        white-space: nowrap;
        border-bottom: 1px solid #f0e4eb;
    }

    .products-table td {
        padding: 16px;
        color: #4a3b47;
        border-bottom: 1px solid #f4edf1;
        vertical-align: middle;
    }

    .products-table tbody tr:hover {
        background: #fffafb;
    }

    .product-name {
        font-weight: 700;
    }

    .product-sku {
        font-size: 12px;
        color: #91818c;
        margin-top: 3px;
    }

    .category-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: #f3e8f1;
        color: #8e5a83;
        font-size: 12px;
        font-weight: 700;
    }

    .quantity {
        font-weight: 800;
    }

    .reorder-level {
        color: #786875;
    }

    .price {
        font-weight: 700;
        white-space: nowrap;
    }

    .supplier {
        color: #6f606b;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
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

    .actions {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 8px;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .view-btn {
        background: #f2ebf5;
        color: #7e4f76;
        border-color: #eaddec;
    }

    .edit-btn {
        background: #fff0f3;
        color: #aa5970;
        border-color: #f6dce3;
    }

    .delete-btn {
        background: #fde8ed;
        color: #b23b58;
        border-color: #f4d0da;
    }

    .delete-form {
        margin: 0;
    }

    .empty-state {
        text-align: center;
        padding: 65px 20px;
    }

    .empty-icon {
        font-size: 45px;
        margin-bottom: 12px;
    }

    .empty-state h3 {
        margin: 0 0 7px;
        color: #4a3b47;
    }

    .empty-state p {
        margin: 0 0 20px;
        color: #887782;
    }

    @media (max-width: 768px) {
        .products-page {
            padding: 20px;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="products-page">

    <div class="page-header">
        <div>
            <h1>Products</h1>
            <p>Manage construction materials and hardware inventory.</p>
        </div>

        <a href="{{ route('products.create') }}" class="btn-add">
            ＋ Add Product
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="products-card" style="padding: 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('products.index') }}"
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search name, SKU, description..."
            style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;"
        >

        <select name="category"
                style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}"
                    @selected(request('category') == $category)>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <select name="supplier_id"
                style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;">
            <option value="">All Suppliers</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @selected(request('supplier_id') == $supplier->id)>
                    {{ $supplier->company_name }}
                </option>
            @endforeach
        </select>

        <select name="stock_status"
                style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;">
            <option value="">All Stock Status</option>
            <option value="in_stock" @selected(request('stock_status') == 'in_stock')>In Stock</option>
            <option value="low_stock" @selected(request('stock_status') == 'low_stock')>Low Stock</option>
            <option value="out_of_stock" @selected(request('stock_status') == 'out_of_stock')>Out of Stock</option>
        </select>

        <select name="sort_by"
                style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;">
            <option value="created_at">Date Added</option>
            <option value="name" @selected(request('sort_by') == 'name')>Product Name</option>
            <option value="sku" @selected(request('sort_by') == 'sku')>SKU</option>
            <option value="quantity" @selected(request('sort_by') == 'quantity')>Quantity</option>
            <option value="unit_price" @selected(request('sort_by') == 'unit_price')>Unit Price</option>
        </select>

        <select name="sort_direction"
                style="padding: 11px; border: 1px solid #eaddec; border-radius: 10px;">
            <option value="desc" @selected(request('sort_direction', 'desc') == 'desc')>Descending</option>
            <option value="asc" @selected(request('sort_direction') == 'asc')>Ascending</option>
        </select>

        <button type="submit"
                style="padding: 11px 18px; border: 0; border-radius: 10px; background: #8e5a83; color: white; font-weight: 700; cursor: pointer;">
            Search & Filter
        </button>

        <a href="{{ route('products.index') }}"
           style="padding: 11px 18px; border-radius: 10px; background: #f3e8f1; color: #8e5a83; text-align: center; text-decoration: none; font-weight: 700;">
            Clear Filters
        </a>
    </form>
</div>

    <div class="products-card">

        @if ($products->count())

            <div class="table-wrapper">
                <table class="products-table">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Reorder Level</th>
                            <th>Unit Price</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($products as $product)

                            @php
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

                            <tr>

                                <td>
                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>

                                    <div class="product-sku">
                                        SKU: {{ $product->sku }}
                                    </div>
                                </td>

                                <td>
                                    <span class="category-badge">
                                        {{ $product->category }}
                                    </span>
                                </td>

                                <td>
                                    <span class="quantity">
                                        {{ $product->quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="reorder-level">
                                        {{ $product->reorder_level }}
                                    </span>
                                </td>

                                <td>
                                    <span class="price">
                                        ₱{{ number_format($product->unit_price, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="supplier">
                                        {{ $product->supplier ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        ● {{ $statusText }}
                                    </span>
                                </td>

                                <td>
                                    <div class="actions">

                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="action-btn view-btn"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('products.destroy', $product) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                            >
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                                </table>
            </div>

            {{-- Pagination --}}
            <div style="padding: 18px;">
                {{ $products->links() }}
            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">📦</div>

                <h3>No Products Found</h3>

                <p>
                    Start adding construction materials to your inventory.
                </p>

                <a href="{{ route('products.create') }}" class="btn-add">
                    ＋ Add Product
                </a>

            </div>

        @endif

    </div>

</div>


</x-app-layout>
