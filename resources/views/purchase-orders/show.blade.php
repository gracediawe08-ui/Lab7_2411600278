<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Details</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #fff7f8;
            color: #4a3b47;
        }
        .container { max-width: 1100px; margin: auto; }
        .header, .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px #8e5a8315;
        }
        h1 { color: #8e5a83; margin-top: 0; }
        h2 { color: #8e5a83; font-size: 19px; }
        .muted { color: #897b87; }
        .details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
        }
        .detail { padding: 12px; background: #fff7f8; border-radius: 9px; }
        .detail small { display: block; color: #897b87; margin-bottom: 7px; }
        .detail strong { overflow-wrap: anywhere; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px; text-align: left; border-bottom: 1px solid #f1e4eb; }
        th { background: #fce4e8; color: #704b67; }
        .total { text-align: right; margin-top: 20px; line-height: 2; }
        .grand { font-size: 22px; font-weight: bold; color: #8e5a83; }
        .btn {
            display: inline-block; border: 0; border-radius: 8px;
            padding: 11px 17px; text-decoration: none; cursor: pointer;
            font-weight: bold; background: #8e5a83; color: white;
        }
        .btn:hover { background: #704565; }
        .secondary { background: #fce4e8; color: #704b67; }
        .danger { background: #b94b65; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        input {
            padding: 9px; border: 1px solid #e5cbd8;
            border-radius: 7px; max-width: 100px;
        }
        textarea {
            width: 100%; padding: 10px; border: 1px solid #e5cbd8;
            border-radius: 7px; margin: 10px 0;
        }
        .status {
            display: inline-block; padding: 6px 12px;
            background: #fce4e8; color: #8e5a83;
            border-radius: 20px; font-weight: bold;
        }
        .form-row { margin: 15px 0; }
        @media(max-width:600px) {
            body { padding: 12px; }
            .header, .card { padding: 16px; }
            table { font-size: 13px; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>Purchase Order Details</h1>
        <p class="muted">View order information, products, and approval status.</p>
        <a href="{{ route('purchase-orders.index') }}" class="btn secondary">← Back to Purchase Orders</a>
    </div>

    @if(session('success'))
        <div class="card">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="card">
            <strong>Please check the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h2>Order Information</h2>
        <div class="details">
            <div class="detail">
                <small>Order Number</small>
                <strong>{{ $purchaseOrder->order_number }}</strong>
            </div>
            <div class="detail">
                <small>Supplier</small>
                <strong>{{ $purchaseOrder->supplier->company_name }}</strong>
            </div>
            <div class="detail">
                <small>Order Date</small>
                <strong>{{ $purchaseOrder->order_date?->format('M d, Y') }}</strong>
            </div>
            <div class="detail">
                <small>Expected Delivery</small>
                <strong>{{ $purchaseOrder->expected_delivery_date?->format('M d, Y') ?? 'Not specified' }}</strong>
            </div>
            <div class="detail">
                <small>Status</small>
                <span class="status">{{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}</span>
            </div>
            <div class="detail">
                <small>Created By</small>
                <strong>{{ $purchaseOrder->creator?->name ?? 'System' }}</strong>
            </div>
            <div class="detail">
                <small>Approved By</small>
                <strong>{{ $purchaseOrder->approver?->name ?? 'Not yet approved' }}</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Ordered Products</h2>
        <div style="overflow-x:auto">
            <table>
                <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Received</th>
                    <th>Unit Cost</th>
                    <th>Line Total</th>
                </tr>
                </thead>
                <tbody>
                @forelse($purchaseOrder->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->sku }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->received_quantity }}</td>
                        <td>₱{{ number_format((float)$item->unit_cost, 2) }}</td>
                        <td>₱{{ number_format((float)$item->line_total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No products found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="total">
            <div>Subtotal: ₱{{ number_format((float)$purchaseOrder->subtotal, 2) }}</div>
            <div>Tax: ₱{{ number_format((float)$purchaseOrder->tax_amount, 2) }}</div>
            <div>Shipping: ₱{{ number_format((float)$purchaseOrder->shipping_cost, 2) }}</div>
            <div class="grand">Grand Total: ₱{{ number_format((float)$purchaseOrder->total_amount, 2) }}</div>
        </div>

        @if($purchaseOrder->notes)
            <hr style="border:0;border-top:1px solid #f1e4eb;margin:20px 0">
            <h2>Order Notes</h2>
            <p>{{ $purchaseOrder->notes }}</p>
        @endif
    </div>

    <div class="card">
        <h2>Purchase Order Workflow</h2>

        <div class="actions">
            @if(in_array($purchaseOrder->status, ['draft', 'rejected']))
                <a class="btn secondary" href="{{ route('purchase-orders.edit', $purchaseOrder) }}">Edit Order</a>

                <form method="POST" action="{{ route('purchase-orders.submit', $purchaseOrder) }}">
                    @csrf
                    <button class="btn" type="submit">Submit for Approval</button>
                </form>

                <form method="POST" action="{{ route('purchase-orders.destroy', $purchaseOrder) }}"
                      onsubmit="return confirm('Delete this purchase order?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Delete Order</button>
                </form>
            @endif

            @if($purchaseOrder->status === 'pending_approval')
                <form method="POST" action="{{ route('purchase-orders.approve', $purchaseOrder) }}">
                    @csrf
                    <button class="btn" type="submit">Approve Order</button>
                </form>

                <form method="POST" action="{{ route('purchase-orders.reject', $purchaseOrder) }}">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Rejection reason" required>
                    <button class="btn danger" type="submit">Reject Order</button>
                </form>
            @endif

            @if($purchaseOrder->status === 'approved')
                <form method="POST" action="{{ route('purchase-orders.send', $purchaseOrder) }}">
                    @csrf
                    <button class="btn" type="submit">Mark as Sent</button>
                </form>
            @endif
        </div>

        @if(in_array($purchaseOrder->status, ['sent', 'partially_received']))
            <h2 style="margin-top:25px">Receive Products</h2>
            <p class="muted">Enter the additional quantity received for each product.</p>

            <form method="POST" action="{{ route('purchase-orders.receive', $purchaseOrder) }}">
                @csrf

                @foreach($purchaseOrder->items as $item)
                    @php
                        $remaining = $item->quantity - $item->received_quantity;
                    @endphp

                    <div class="form-row">
                        <label>
                            <strong>{{ $item->product->name }}</strong>
                            <span class="muted">
                                — Remaining: {{ $remaining }}
                            </span>
                        </label>
                        <br>
                        <input type="number"
                               name="received_quantities[{{ $item->id }}]"
                               min="0"
                               max="{{ $remaining }}"
                               value="0"
                               required>
                    </div>
                @endforeach

                <button type="submit" class="btn">Confirm Received Stock</button>
            </form>
        @endif
    </div>

</div>
</body>
</html>
