<x-app-layout>


<style>
    .inventory-page {
        max-width: 1200px;
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
        color: #4a3b47;
        font-size: 30px;
        font-weight: 700;
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

    .transaction-card {
        background: white;
        border: 1px solid #f0e4eb;
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(92, 63, 83, 0.08);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .transaction-table th {
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

    .transaction-table td {
        padding: 16px;
        color: #4a3b47;
        border-bottom: 1px solid #f4edf1;
        vertical-align: middle;
    }

    .transaction-table tbody tr:hover {
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

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .stock-in {
        background: #e8f6ee;
        color: #2e7d52;
    }

    .stock-out {
        background: #fde8ed;
        color: #b23b58;
    }

    .quantity {
        font-weight: 800;
        font-size: 16px;
    }

    .reference {
        color: #6f606b;
    }

    .user-name {
        font-size: 13px;
        font-weight: 600;
    }

    .date-time {
        font-size: 13px;
        color: #786875;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 42px;
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

    .summary-box {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .summary-item {
        background: linear-gradient(135deg, #faf0f7, #fff2f4);
        border: 1px solid #f0e2e9;
        border-radius: 15px;
        padding: 18px;
    }

    .summary-label {
        font-size: 12px;
        font-weight: 700;
        color: #8a7784;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .summary-number {
        margin-top: 5px;
        color: #8e5a83;
        font-size: 25px;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .inventory-page {
            padding: 20px;
        }

        .summary-box {
            grid-template-columns: 1fr;
        }

        .page-header {
            align-items: flex-start;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="inventory-page">

    <div class="page-header">
        <div>
            <h1>Inventory Transactions</h1>
            <p>Track stock-in and stock-out activities for construction materials.</p>
        </div>

        <a href="{{ route('inventory.create') }}" class="btn-add">
            ＋ Record Transaction
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @php
        $totalTransactions = $transactions->count();
        $stockInCount = $transactions->where('transaction_type', 'stock_in')->count();
        $stockOutCount = $transactions->where('transaction_type', 'stock_out')->count();
    @endphp

    <div class="summary-box">

        <div class="summary-item">
            <div class="summary-label">Total Transactions</div>
            <div class="summary-number">
                {{ $totalTransactions }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Stock In</div>
            <div class="summary-number">
                {{ $stockInCount }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Stock Out</div>
            <div class="summary-number">
                {{ $stockOutCount }}
            </div>
        </div>

    </div>

    <div class="transaction-card">

        @if ($transactions->count())

            <div class="table-wrapper">
                <table class="transaction-table">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reference</th>
                            <th>Recorded By</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($transactions as $transaction)

                            <tr>

                                <td>
                                    <div class="product-name">
                                        {{ $transaction->product->name }}
                                    </div>

                                    <div class="product-sku">
                                        SKU: {{ $transaction->product->sku }}
                                    </div>
                                </td>

                                <td>

                                    @if ($transaction->transaction_type === 'stock_in')

                                        <span class="type-badge stock-in">
                                            ↑ Stock In
                                        </span>

                                    @else

                                        <span class="type-badge stock-out">
                                            ↓ Stock Out
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    <span class="quantity">
                                        {{ $transaction->quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="reference">
                                        {{ $transaction->reference_document ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="user-name">
                                        {{ $transaction->user->name ?? 'Administrator' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="date-time">
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">📦</div>

                <h3>No Inventory Transactions Yet</h3>

                <p>
                    Record your first stock-in or stock-out transaction.
                </p>

                <a href="{{ route('inventory.create') }}" class="btn-add">
                    ＋ Record Transaction
                </a>

            </div>

        @endif

    </div>

</div>


</x-app-layout>
