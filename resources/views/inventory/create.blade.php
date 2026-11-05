<x-app-layout>

<style>
    .inventory-form-page {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0;
        color: #4a3b47;
        font-size: 30px;
        font-weight: 700;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #7b6b77;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #f0e4eb;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 35px rgba(92, 63, 83, 0.08);
    }

    .form-section-title {
        margin: 0 0 20px;
        color: #8e5a83;
        font-size: 18px;
        font-weight: 800;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 8px;
        color: #5c4b56;
        font-size: 13px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #dfd2db;
        border-radius: 10px;
        background: #fffafb;
        color: #4a3b47;
        font-size: 14px;
        outline: none;
        transition: 0.2s ease;
    }

    .form-control:focus {
        border-color: #8e5a83;
        box-shadow: 0 0 0 3px rgba(142, 90, 131, 0.10);
        background: #ffffff;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .transaction-type {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .type-option {
        position: relative;
    }

    .type-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .type-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        border: 1px solid #dfd2db;
        border-radius: 12px;
        background: #fffafb;
        color: #6b5965;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .type-option label:hover {
        border-color: #c98da3;
    }

    .type-option input:checked + label {
        background: linear-gradient(135deg, #f3e7f2, #fdecef);
        border-color: #8e5a83;
        color: #8e5a83;
    }

    .hint {
        margin-top: 6px;
        color: #8b7b86;
        font-size: 12px;
    }

    .error-message {
        margin-top: 6px;
        color: #b23b58;
        font-size: 12px;
        font-weight: 600;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 12px;
        background: #fde8ed;
        border: 1px solid #f3ccd7;
        color: #a63d5d;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #f0e4eb;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 18px;
        border-radius: 10px;
        border: none;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
    }

    .btn-cancel {
        background: #f3e8f1;
        color: #8e5a83;
    }

    .btn-save {
        background: linear-gradient(135deg, #8e5a83, #c06c84);
        color: white;
        box-shadow: 0 7px 18px rgba(142, 90, 131, 0.18);
    }

    @media (max-width: 700px) {
        .inventory-form-page {
            padding: 20px;
        }

        .form-card {
            padding: 22px;
        }

        .form-grid,
        .transaction-type {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="inventory-form-page">

    <div class="page-header">
        <h1>Record Inventory Transaction</h1>
        <p>
            Add incoming stock or record outgoing stock for a hardware product.
        </p>
    </div>

    <div class="form-card">

        <h2 class="form-section-title">
            Transaction Information
        </h2>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please correct the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventory.store') }}" method="POST">

            @csrf

            <div class="form-grid">

                <div class="form-group full">
                    <label class="form-label">
                        Product
                    </label>

                    <select
                        name="product_id"
                        id="product_id"
                        class="form-control"
                        required
                    >
                        <option value="">-- Select Product --</option>

                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-stock="{{ $product->quantity }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}
                            >
                                {{ $product->name }} ({{ $product->sku }}) — Current Stock: {{ $product->quantity }}
                            </option>
                        @endforeach
                    </select>

                    <div class="hint" id="stockHint">
                        Select a product to view its current stock.
                    </div>

                    @error('product_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">
                        Transaction Type
                    </label>

                    <div class="transaction-type">

                        <div class="type-option">
                            <input
                                type="radio"
                                name="transaction_type"
                                id="stock_in"
                                value="stock_in"
                                {{ old('transaction_type', 'stock_in') === 'stock_in' ? 'checked' : '' }}
                            >

                            <label for="stock_in">
                                ↑ Stock In
                            </label>
                        </div>

                        <div class="type-option">
                            <input
                                type="radio"
                                name="transaction_type"
                                id="stock_out"
                                value="stock_out"
                                {{ old('transaction_type') === 'stock_out' ? 'checked' : '' }}
                            >

                            <label for="stock_out">
                                ↓ Stock Out
                            </label>
                        </div>

                    </div>

                    <div class="hint">
                        Stock In increases inventory. Stock Out decreases inventory.
                    </div>

                    @error('transaction_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity" class="form-label">
                        Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        class="form-control"
                        min="1"
                        value="{{ old('quantity') }}"
                        placeholder="Enter quantity"
                        required
                    >

                    <div class="hint" id="quantityHint">
                        Enter the number of units.
                    </div>

                    @error('quantity')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reference_document" class="form-label">
                        Reference Document
                    </label>

                    <input
                        type="text"
                        name="reference_document"
                        id="reference_document"
                        class="form-control"
                        value="{{ old('reference_document') }}"
                        maxlength="255"
                        placeholder="e.g. PO-1001, INV-2001"
                    >

                    <div class="hint">
                        Optional purchase order, invoice, or delivery reference.
                    </div>

                    @error('reference_document')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('inventory.index') }}"
                    class="btn btn-cancel"
                >
                    ← Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    ✓ Record Transaction
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    const productSelect = document.getElementById('product_id');
    const stockHint = document.getElementById('stockHint');
    const quantityInput = document.getElementById('quantity');
    const quantityHint = document.getElementById('quantityHint');
    const stockOutRadio = document.getElementById('stock_out');

    function updateStockInfo() {
        const selectedOption =
            productSelect.options[productSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            stockHint.textContent =
                'Select a product to view its current stock.';

            quantityInput.removeAttribute('max');

            quantityHint.textContent =
                'Enter the number of units.';

            return;
        }

        const currentStock =
            selectedOption.dataset.stock;

        stockHint.textContent =
            'Current stock: ' + currentStock + ' unit(s).';

        if (stockOutRadio.checked) {
            quantityInput.setAttribute('max', currentStock);

            quantityHint.textContent =
                'For Stock Out, quantity cannot exceed the current stock of ' +
                currentStock +
                ' unit(s).';
        } else {
            quantityInput.removeAttribute('max');

            quantityHint.textContent =
                'Stock In will increase the current stock.';
        }
    }

    productSelect.addEventListener(
        'change',
        updateStockInfo
    );

    document
        .querySelectorAll('input[name="transaction_type"]')
        .forEach(function (radio) {
            radio.addEventListener(
                'change',
                updateStockInfo
            );
        });

    updateStockInfo();
</script>


</x-app-layout>
