<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchase Order | Inventory Management</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff7f8;
            color: #4a3b47;
        }

        .container {
            max-width: 1050px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 24px;
        }

        h1 { color: #754766; margin: 0 0 8px; }
        .subtitle { color: #887783; margin: 0; }

        .panel {
            background: white;
            padding: 25px;
            border: 1px solid #f0dce6;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(100, 55, 80, .05);
            margin-bottom: 20px;
        }

        h3 {
            color: #754766;
            margin: 0 0 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 13px;
            font-weight: bold;
            color: #654b60;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e5cbd9;
            border-radius: 8px;
            background: white;
            color: #4a3b47;
            font: inherit;
            font-size: 13px;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #e8b8cd;
            border-color: #c06c84;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .full { grid-column: 1 / -1; }

        .button {
            display: inline-block;
            padding: 11px 17px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
        }

        .primary { background: #8e5a83; color: white; }
        .secondary { background: #f3e5ed; color: #754766; }
        .danger { background: #fce4e8; color: #a33d58; }

        .items-table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #f1e5eb;
        }

        th {
            font-size: 11px;
            text-transform: uppercase;
            color: #754766;
            background: #fff7f8;
        }

        td input, td select { min-width: 100px; }

        .remove-btn {
            border: none;
            background: #fce4e8;
            color: #a33d58;
            border-radius: 7px;
            padding: 9px 11px;
            cursor: pointer;
            font-weight: bold;
        }

        .totals {
            max-width: 380px;
            margin-left: auto;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f1e5eb;
            font-size: 14px;
        }

        .grand-total {
            font-size: 19px;
            font-weight: bold;
            color: #754766;
            border-bottom: none;
            padding-top: 16px;
        }

        .error-box {
            background: #fce4e8;
            color: #a33d58;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hint {
            font-size: 12px;
            color: #968493;
            margin-top: 5px;
        }

        @media(max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .container { margin-top: 20px; }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Create Purchase Order</h1>
            <p class="subtitle">Prepare a new order for your registered supplier.</p>
        </div>

        <a href="{{ route('purchase-orders.index') }}" class="button secondary">
            Back to Purchase Orders
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <strong>Please correct the following:</strong>
            <ul style="margin-bottom:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('purchase-orders.store') }}" id="purchaseOrderForm">
        @csrf

        <div class="panel">
            <h3>Order Information</h3>

            <div class="form-grid">
                <div class="field">
                    <label for="supplier_id">Supplier *</label>

                    <select name="supplier_id" id="supplier_id" required>
                        <option value="">Select Supplier</option>

                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                @selected(old('supplier_id') == $supplier->id)>
                                {{ $supplier->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="order_date">Order Date *</label>
                    <input
                        type="date"
                        name="order_date"
                        id="order_date"
                        value="{{ old('order_date', now()->format('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="expected_delivery_date">Expected Delivery Date</label>
                    <input
                        type="date"
                        name="expected_delivery_date"
                        id="expected_delivery_date"
                        value="{{ old('expected_delivery_date') }}"
                    >
                </div>

                <div class="field">
                    <label>Order Status</label>
                    <input type="text" value="Draft (Pending Submission)" disabled>
                </div>
            </div>
        </div>

        <div class="panel">
            <h3>Order Products</h3>
            <p class="hint" style="margin-top:-12px;margin-bottom:18px;">
                Select a supplier first. Only products registered under that supplier will be available.
            </p>

            <div class="items-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:35%;">Product *</th>
                            <th>Quantity *</th>
                            <th>Unit Cost (₱) *</th>
                            <th>Line Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][product_id]" class="product-select" required>
                                    <option value="">Select Product</option>

                                    @foreach($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-supplier="{{ $product->supplier_id }}"
                                            data-cost="{{ $product->unit_price }}"
                                            @if(old('items.0.product_id') == $product->id) selected @endif
                                        >
                                            {{ $product->name }} ({{ $product->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input
                                    type="number"
                                    name="items[0][quantity]"
                                    class="quantity-input"
                                    min="1"
                                    value="{{ old('items.0.quantity', 1) }}"
                                    required
                                >
                            </td>

                            <td>
                                <input
                                    type="number"
                                    name="items[0][unit_cost]"
                                    class="cost-input"
                                    min="0.01"
                                    step="0.01"
                                    value="{{ old('items.0.unit_cost') }}"
                                    placeholder="0.00"
                                    required
                                >
                            </td>

                            <td class="line-total">₱0.00</td>

                            <td>
                                <button type="button" class="remove-btn" onclick="removeRow(this)">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:18px;">
                <button type="button" class="button secondary" onclick="addRow()">
                    + Add Another Product
                </button>
            </div>
        </div>

        <div class="panel">
            <h3>Additional Charges and Notes</h3>

            <div class="form-grid">
                <div class="field">
                    <label for="tax_amount">Tax Amount (₱)</label>
                    <input
                        type="number"
                        name="tax_amount"
                        id="tax_amount"
                        min="0"
                        step="0.01"
                        value="{{ old('tax_amount', 0) }}"
                    >
                </div>

                <div class="field">
                    <label for="shipping_cost">Shipping Cost (₱)</label>
                    <input
                        type="number"
                        name="shipping_cost"
                        id="shipping_cost"
                        min="0"
                        step="0.01"
                        value="{{ old('shipping_cost', 0) }}"
                    >
                </div>

                <div class="field full">
                    <label for="notes">Order Notes</label>
                    <textarea
                        name="notes"
                        id="notes"
                        placeholder="Enter additional instructions or order details..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <strong id="subtotalDisplay">₱0.00</strong>
                </div>

                <div class="total-row">
                    <span>Tax</span>
                    <strong id="taxDisplay">₱0.00</strong>
                </div>

                <div class="total-row">
                    <span>Shipping</span>
                    <strong id="shippingDisplay">₱0.00</strong>
                </div>

                <div class="total-row grand-total">
                    <span>Grand Total</span>
                    <strong id="grandTotalDisplay">₱0.00</strong>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('purchase-orders.index') }}" class="button secondary">
                Cancel
            </a>

            <button type="submit" class="button primary">
                Save Purchase Order
            </button>
        </div>

    </form>
</div>

<script>
    let rowIndex = 1;

   const productOptions = {{ Illuminate\Support\Js::from(
    $products->map(fn ($product) => [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'supplier_id' => $product->supplier_id,
        'unit_price' => $product->unit_price,
    ])->values()
) }};

    const supplierSelect = document.getElementById('supplier_id');
    const itemsBody = document.getElementById('itemsBody');

    function money(value) {
        return '₱' + Number(value || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function productOptionsHtml() {
        let html = '<option value="">Select Product</option>';

        productOptions.forEach(product => {
            html += `
                <option
                    value="${product.id}"
                    data-supplier="${product.supplier_id ?? ''}"
                    data-cost="${product.unit_price}"
                >
                    ${escapeHtml(product.name)} (${escapeHtml(product.sku)})
                </option>
            `;
        });

        return html;
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[char]);
    }

    function filterProducts(select) {
        const supplierId = supplierSelect.value;

        select.querySelectorAll('option').forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const belongsToSupplier = option.dataset.supplier === supplierId;
            option.hidden = !supplierId || !belongsToSupplier;
        });

        const selected = select.options[select.selectedIndex];

        if (selected && selected.hidden) {
            select.value = '';
        }
    }

    function addRow() {
        const row = document.createElement('tr');
        row.className = 'item-row';

        row.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="product-select" required>
                    ${productOptionsHtml()}
                </select>
            </td>

            <td>
                <input
                    type="number"
                    name="items[${rowIndex}][quantity]"
                    class="quantity-input"
                    min="1"
                    value="1"
                    required
                >
            </td>

            <td>
                <input
                    type="number"
                    name="items[${rowIndex}][unit_cost]"
                    class="cost-input"
                    min="0.01"
                    step="0.01"
                    placeholder="0.00"
                    required
                >
            </td>

            <td class="line-total">₱0.00</td>

            <td>
                <button type="button" class="remove-btn" onclick="removeRow(this)">
                    Remove
                </button>
            </td>
        `;

        itemsBody.appendChild(row);

        const select = row.querySelector('.product-select');
        filterProducts(select);

        rowIndex++;
        calculateTotals();
    }

    function removeRow(button) {
        const rows = itemsBody.querySelectorAll('.item-row');

        if (rows.length <= 1) {
            alert('At least one product is required.');
            return;
        }

        button.closest('tr').remove();
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = Number(row.querySelector('.quantity-input').value) || 0;
            const cost = Number(row.querySelector('.cost-input').value) || 0;
            const lineTotal = quantity * cost;

            row.querySelector('.line-total').textContent = money(lineTotal);
            subtotal += lineTotal;
        });

        const tax = Number(document.getElementById('tax_amount').value) || 0;
        const shipping = Number(document.getElementById('shipping_cost').value) || 0;
        const grandTotal = subtotal + tax + shipping;

        document.getElementById('subtotalDisplay').textContent = money(subtotal);
        document.getElementById('taxDisplay').textContent = money(tax);
        document.getElementById('shippingDisplay').textContent = money(shipping);
        document.getElementById('grandTotalDisplay').textContent = money(grandTotal);
    }

    supplierSelect.addEventListener('change', function () {
        document.querySelectorAll('.product-select').forEach(select => {
            select.value = '';
            filterProducts(select);
        });

        document.querySelectorAll('.cost-input').forEach(input => {
            input.value = '';
        });

        calculateTotals();
    });

    itemsBody.addEventListener('change', function (event) {
        if (event.target.classList.contains('product-select')) {
            const selected = event.target.options[event.target.selectedIndex];
            const row = event.target.closest('tr');
            const costInput = row.querySelector('.cost-input');

            costInput.value = selected.dataset.cost || '';
            calculateTotals();
        }
    });

    itemsBody.addEventListener('input', function (event) {
        if (
            event.target.classList.contains('quantity-input') ||
            event.target.classList.contains('cost-input')
        ) {
            calculateTotals();
        }
    });

    document.getElementById('tax_amount').addEventListener('input', calculateTotals);
    document.getElementById('shipping_cost').addEventListener('input', calculateTotals);

    // Filter the first product dropdown when the page loads.
    document.querySelectorAll('.product-select').forEach(filterProducts);

    // Recalculate the total when the page loads.
    calculateTotals();
</script>

</body>
</html>
