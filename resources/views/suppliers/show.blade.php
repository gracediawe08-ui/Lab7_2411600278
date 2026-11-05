<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Details - Hardware Inventory System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #FFF7F8;
            color: #4A3B47;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 1150px;
            margin: auto;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            color: #4A3B47;
        }

        .page-header p {
            color: #9A8B95;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-back {
            background: white;
            color: #8E5A83;
            border: 1px solid #E8D5DF;
        }

        .btn-edit {
            background: #8E5A83;
            color: white;
        }

        .card {
            background: white;
            border: 1px solid #F1E5EA;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 22px;
            box-shadow: 0 5px 18px rgba(74,59,71,.05);
        }

        .card h2 {
            margin: 0 0 7px;
            font-size: 19px;
        }

        .subtitle {
            color: #9A8B95;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .supplier-name {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status {
            display: inline-block;
            margin-top: 12px;
            padding: 6px 13px;
            border-radius: 20px;
            background: #EAF6EF;
            color: #43815E;
            font-size: 12px;
            font-weight: bold;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-top: 25px;
        }

        .detail-label {
            color: #9A8B95;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            padding: 14px;
            background: #FFF7F8;
            color: #8E5A83;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #F1E5EA;
        }

        .total {
            margin-top: 18px;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
        }

        @media(max-width: 650px) {
            body {
                padding: 16px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="page-header">
        <h1>Supplier Details</h1>
        <p>View supplier information and associated products.</p>
    </div>

    <div class="actions">
        <a href="{{ route('suppliers.index') }}" class="btn btn-back">
            Back to Suppliers
        </a>

        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-edit">
            Edit Supplier
        </a>
    </div>

    <div class="card">
        <h2>Registered Supplier</h2>
        <p class="subtitle">Supplier company and contact information.</p>

        <div class="supplier-name">{{ $supplier->company_name }}</div>
        <div>{{ $supplier->contact_person }}</div>

        <span class="status">
            {{ ucfirst($supplier->status) }}
        </span>

        <div class="details-grid">
            <div>
                <div class="detail-label">Contact Person</div>
                <div class="detail-value">{{ $supplier->contact_person ?: 'Not Provided' }}</div>
            </div>

            <div>
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ $supplier->email ?: 'No email provided' }}</div>
            </div>

            <div>
                <div class="detail-label">Phone Number</div>
                <div class="detail-value">{{ $supplier->phone ?: 'No phone provided' }}</div>
            </div>

            <div>
                <div class="detail-label">Business Address</div>
                <div class="detail-value">{{ $supplier->address ?: 'No address provided' }}</div>
            </div>

            <div>
                <div class="detail-label">Payment Terms</div>
                <div class="detail-value">{{ $supplier->payment_terms }}</div>
            </div>

            <div>
                <div class="detail-label">Lead Time</div>
                <div class="detail-value">{{ $supplier->lead_time_days }} days</div>
            </div>

            <div>
                <div class="detail-label">Minimum Order Amount</div>
                <div class="detail-value">₱{{ number_format((float) $supplier->minimum_order_amount, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Supplied Products</h2>
        <p class="subtitle">Products registered under this supplier.</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Stock Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($supplier->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>₱{{ number_format((float) $product->unit_price, 2) }}</td>
                            <td>
                                @if($product->quantity == 0)
                                    Out of Stock
                                @elseif($product->quantity <= $product->reorder_level)
                                    Low Stock
                                @else
                                    In Stock
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:25px;">
                                No products linked to this supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="total">
            Total linked products: {{ $supplier->products->count() }}
        </div>
    </div>

</div>
</body>
</html>