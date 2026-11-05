<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders | Inventory Management</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff7f8;
            color: #4a3b47;
        }

        .container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 22px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 { margin: 0 0 8px; color: #754766; }
        .subtitle { color: #887783; margin: 0; }

        .button {
            display: inline-block;
            padding: 11px 17px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
        }

        .primary { background: #8e5a83; color: white; }
        .secondary { background: #f3e5ed; color: #754766; }
        .danger { background: #fce4e8; color: #a33d58; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 25px;
        }

        .stat-card, .panel {
            background: white;
            border: 1px solid #f0dce6;
            border-radius: 13px;
            box-shadow: 0 4px 15px rgba(100, 55, 80, .05);
        }

        .stat-card { padding: 20px; }
        .stat-card p { margin: 0 0 10px; color: #8d7b87; font-size: 13px; }
        .stat-card h2 { margin: 0; color: #754766; font-size: 27px; }

        .panel { padding: 22px; }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        input, select {
            padding: 11px 12px;
            border: 1px solid #e5cbd9;
            border-radius: 8px;
            background: white;
            color: #4a3b47;
            min-width: 150px;
        }

        input:focus, select:focus {
            outline: 2px solid #e8b8cd;
            border-color: #c06c84;
        }

        .filters input { flex: 1; min-width: 220px; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #f1e5eb;
            font-size: 13px;
        }

        th {
            background: #fff7f8;
            color: #754766;
            font-size: 12px;
            text-transform: uppercase;
        }

        tr:hover td { background: #fffbfc; }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: capitalize;
            background: #f2edf0;
            color: #6d5966;
        }

        .status-draft { background: #f1edf2; color: #746477; }
        .status-pending_approval { background: #fff0d8; color: #94621c; }
        .status-approved { background: #e8f4ed; color: #34734c; }
        .status-sent { background: #e9efff; color: #4c6298; }
        .status-partially_received { background: #fcebdc; color: #a45c25; }
        .status-received { background: #e5f5ed; color: #28734a; }
        .status-rejected { background: #fce4e8; color: #a33d58; }

        .actions { display: flex; gap: 7px; align-items: center; }
        .actions .button { padding: 7px 10px; font-size: 11px; }
        .actions form { margin: 0; }

        .alert {
            padding: 13px 16px;
            margin-bottom: 18px;
            border-radius: 8px;
            background: #e8f4ed;
            color: #34734c;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #8d7b87;
        }

        .pagination { margin-top: 20px; }

        @media (max-width: 800px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .stats { grid-template-columns: 1fr; }
            .container { margin-top: 20px; }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="topbar">
        <div>
            <h1>Purchase Order Management</h1>
            <p class="subtitle">Create, monitor, approve, and receive supplier orders.</p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('dashboard') }}" class="button secondary">Back to Dashboard</a>
            <a href="{{ route('purchase-orders.create') }}" class="button primary">+ Create Purchase Order</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#fce4e8;color:#a33d58;">
            Please check the form information. {{ $errors->first() }}
        </div>
    @endif

    <div class="stats">
        <div class="stat-card">
            <p>Total Purchase Orders</p>
            <h2>{{ $totalOrders }}</h2>
        </div>

        <div class="stat-card">
            <p>Pending Approval</p>
            <h2>{{ $pendingOrders }}</h2>
        </div>

        <div class="stat-card">
            <p>Approved Orders</p>
            <h2>{{ $approvedOrders }}</h2>
        </div>

        <div class="stat-card">
            <p>Received Orders</p>
            <h2>{{ $receivedOrders }}</h2>
        </div>
    </div>

    <div class="panel">
        <h3 style="margin-top:0;color:#754766;">Purchase Order Records</h3>

        <form method="GET" action="{{ route('purchase-orders.index') }}" class="filters">
            <input
                type="text"
                name="search"
                placeholder="Search order number or supplier..."
                value="{{ request('search') }}"
            >

            <select name="status">
                <option value="">All Statuses</option>
                @foreach([
                    'draft' => 'Draft',
                    'pending_approval' => 'Pending Approval',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'sent' => 'Sent',
                    'partially_received' => 'Partially Received',
                    'received' => 'Received'
                ] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="supplier_id">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}"
                        @selected((string) request('supplier_id') === (string) $supplier->id)>
                        {{ $supplier->company_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="button primary">Apply Filters</button>
            <a href="{{ route('purchase-orders.index') }}" class="button secondary">Clear</a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>

                            <td>{{ $order->supplier->company_name ?? 'Supplier unavailable' }}</td>

                            <td>{{ $order->order_date?->format('M d, Y') ?? '—' }}</td>

                            <td>{{ $order->expected_delivery_date?->format('M d, Y') ?? 'Not set' }}</td>

                            <td>{{ $order->items_count }}</td>

                            <td>₱{{ number_format((float) $order->total_amount, 2) }}</td>

                            <td>
                                <span class="status status-{{ $order->status }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('purchase-orders.show', $order) }}"
                                       class="button secondary">View</a>

                                    @if(in_array($order->status, ['draft', 'rejected']))
                                        <a href="{{ route('purchase-orders.edit', $order) }}"
                                           class="button primary">Edit</a>

                                        <form method="POST"
                                              action="{{ route('purchase-orders.destroy', $order) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this purchase order?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                No purchase orders found. Click "Create Purchase Order" to add your first order.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </div>

</div>
</body>
</html>
