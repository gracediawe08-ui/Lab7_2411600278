<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Performance Report | Inventory System</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #FFF7F8;
            color: #4A3B47;
            padding: 30px;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 28px;
        }

        h1 {
            color: #8E5A83;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #806F7B;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 11px 17px;
            border-radius: 9px;
            text-decoration: none;
            background: #8E5A83;
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn:hover {
            background: #C06C84;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            border: 1px solid #F0DDE7;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 4px 14px rgba(142, 90, 131, .06);
        }

        .card-label {
            color: #806F7B;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .card-value {
            color: #8E5A83;
            font-size: 27px;
            font-weight: bold;
        }

        .report {
            background: white;
            border: 1px solid #F0DDE7;
            border-radius: 14px;
            padding: 22px;
            overflow-x: auto;
        }

        .report h2 {
            color: #8E5A83;
            margin-bottom: 18px;
            font-size: 19px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th, td {
            padding: 15px 12px;
            border-bottom: 1px solid #F3E5EC;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #FCE4E8;
            color: #754B70;
        }

        tr:hover td {
            background: #FFF9FA;
        }

        .supplier-name {
            font-weight: bold;
            color: #8E5A83;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            background: #FCE4E8;
            color: #8E5A83;
            font-size: 11px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #806F7B;
        }

        .footer {
            margin-top: 22px;
            color: #806F7B;
            font-size: 12px;
            text-align: right;
        }

        @media(max-width: 600px) {
            body {
                padding: 15px;
            }

            .card-value {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Supplier Performance Report</h1>
            <p class="subtitle">
                Supplier purchasing, delivery, and fulfillment analysis
            </p>
        </div>

        <a href="{{ route('suppliers.index') }}" class="btn">
            Back to Suppliers
        </a>
    </div>

    <div class="summary">
        <div class="card">
            <div class="card-label">Total Suppliers</div>
            <div class="card-value">
                {{ number_format($summary['total_suppliers']) }}
            </div>
        </div>

        <div class="card">
            <div class="card-label">Total Purchase Orders</div>
            <div class="card-value">
                {{ number_format($summary['total_orders']) }}
            </div>
        </div>

        <div class="card">
            <div class="card-label">Total Purchase Value</div>
            <div class="card-value">
                ₱{{ number_format($summary['total_purchase_value'], 2) }}
            </div>
        </div>

        <div class="card">
            <div class="card-label">Average On-Time Delivery</div>
            <div class="card-value">
                {{ $summary['average_on_time_rate'] !== null
                    ? number_format($summary['average_on_time_rate'], 2) . '%'
                    : 'N/A' }}
            </div>
        </div>
    </div>

    <div class="report">
        <h2>Supplier Performance Details</h2>

        @if($suppliers->isEmpty())
            <div class="empty">
                No supplier records available.
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Total Orders</th>
                        <th>Purchase Value</th>
                        <th>On-Time Delivery</th>
                        <th>Average Delivery</th>
                        <th>Fulfillment Accuracy</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>
                                <span class="supplier-name">
                                    {{ $supplier->company_name }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($supplier->total_orders) }}
                            </td>

                            <td>
                                ₱{{ number_format($supplier->total_purchase_value, 2) }}
                            </td>

                            <td>
                                {{ $supplier->on_time_delivery_rate !== null
                                    ? number_format($supplier->on_time_delivery_rate, 2) . '%'
                                    : 'N/A' }}
                            </td>

                            <td>
                                {{ $supplier->average_delivery_days !== null
                                    ? number_format($supplier->average_delivery_days, 2) . ' days'
                                    : 'N/A' }}
                            </td>

                            <td>
                                {{ $supplier->fulfillment_accuracy !== null
                                    ? number_format($supplier->fulfillment_accuracy, 2) . '%'
                                    : 'N/A' }}
                            </td>

                            <td>
                                <span class="status">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="footer">
        Generated: {{ now()->format('F d, Y h:i A') }}
    </div>

</div>
</body>
</html>
