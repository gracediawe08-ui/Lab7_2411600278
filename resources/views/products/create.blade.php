<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Product - Hardware Inventory System</title>


    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #FFF7F8 0%,
                    #FCE4E8 100%
                );

            color: #4A3B47;

            min-height: 100vh;

            padding: 30px 20px;
        }


        .page-container {
            max-width: 900px;

            margin: 0 auto;
        }


        .page-header {
            display: flex;

            justify-content: space-between;
            align-items: center;

            gap: 20px;

            margin-bottom: 20px;
        }


        .header-title {
            margin: 0;

            color: #4A3B47;

            font-size: 28px;
            font-weight: 800;
        }


        .header-subtitle {
            margin-top: 5px;

            color: #8A7B86;

            font-size: 13px;
        }


        .back-button {
            display: inline-block;

            background: white;

            color: #8E5A83;

            text-decoration: none;

            padding: 11px 16px;

            border-radius: 11px;

            border: 1px solid #E8DCE2;

            font-size: 12px;
            font-weight: 800;

            transition: .2s ease;
        }


        .back-button:hover {
            transform: translateY(-2px);

            background: #FDF7FA;
        }


        .form-card {
            background: white;

            border: 1px solid #F1E5EA;

            border-radius: 20px;

            padding: 28px;

            box-shadow:
                0 10px 28px rgba(74,59,71,.07);
        }


        .form-heading {
            margin-bottom: 25px;

            padding-bottom: 18px;

            border-bottom: 1px solid #F0E5E9;
        }


        .form-heading h2 {
            margin: 0;

            color: #8E5A83;

            font-size: 19px;
            font-weight: 800;
        }


        .form-heading p {
            margin-top: 5px;

            color: #9A8B95;

            font-size: 11px;
        }


        .error-summary {
            background: #FCE7E9;

            color: #A33F4A;

            border: 1px solid #F5CDD2;

            border-radius: 12px;

            padding: 13px 15px;

            margin-bottom: 20px;

            font-size: 12px;
        }


        .error-summary strong {
            display: block;

            margin-bottom: 5px;
        }


        .error-summary ul {
            margin: 0;

            padding-left: 18px;
        }


        .form-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 18px;
        }


        .full-width {
            grid-column: 1 / -1;
        }


        .form-group {
            margin-bottom: 2px;
        }


        label {
            display: block;

            margin-bottom: 7px;

            color: #4A3B47;

            font-size: 12px;

            font-weight: 800;
        }


        .required {
            color: #C06C84;
        }


        input,
        textarea,
        select {
            width: 100%;

            border: 1px solid #E7DCE2;

            background: #FFFCFD;

            border-radius: 10px;

            padding: 11px 12px;

            color: #4A3B47;

            font-family: inherit;

            font-size: 12px;

            outline: none;

            transition: .2s ease;
        }


        textarea {
            min-height: 110px;

            resize: vertical;
        }


        input:focus,
        textarea:focus,
        select:focus {
            border-color: #E5989B;

            background: white;

            box-shadow:
                0 0 0 3px rgba(229,152,155,.10);
        }


        input::placeholder,
        textarea::placeholder {
            color: #B2A5AD;
        }


        .field-error {
            margin-top: 5px;

            color: #C45C67;

            font-size: 10px;

            font-weight: 600;
        }


        .input-error {
            border-color: #D8757F !important;

            background: #FFF8F9;
        }


        .helper-text {
            margin-top: 5px;

            color: #9A8B95;

            font-size: 9px;
        }


        .buttons {
            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 27px;

            padding-top: 20px;

            border-top: 1px solid #F0E5E9;
        }


        .btn {
            display: inline-block;

            border: none;

            border-radius: 10px;

            padding: 11px 18px;

            font-size: 12px;

            font-weight: 800;

            text-decoration: none;

            cursor: pointer;

            transition: .2s ease;
        }


        .save-button {
            background:
                linear-gradient(
                    135deg,
                    #8E5A83,
                    #E5989B
                );

            color: white;

            box-shadow:
                0 6px 14px rgba(142,90,131,.18);
        }


        .save-button:hover {
            transform: translateY(-2px);
        }


        .cancel-button {
            background: #F2ECEF;

            color: #4A3B47;
        }


        .cancel-button:hover {
            background: #E8E0E4;
        }


        .info-card {
            margin-top: 18px;

            background:
                linear-gradient(
                    135deg,
                    #8E5A83,
                    #C06C84
                );

            border-radius: 16px;

            padding: 17px 19px;

            color: white;
        }


        .info-title {
            margin: 0;

            font-size: 13px;

            font-weight: 800;
        }


        .info-text {
            margin-top: 5px;

            font-size: 10px;

            line-height: 1.5;

            opacity: .88;
        }


        @media (max-width: 700px) {

            body {
                padding: 20px 14px;
            }


            .page-header {
                flex-direction: column;

                align-items: flex-start;
            }


            .form-card {
                padding: 20px;
            }


            .form-grid {
                grid-template-columns: 1fr;
            }


            .full-width {
                grid-column: auto;
            }


            .buttons {
                flex-direction: column-reverse;
            }


            .btn {
                width: 100%;

                text-align: center;
            }

        }

    </style>

</head>


<body>


    <div class="page-container">


        <div class="page-header">


            <div>

                <h1 class="header-title">
                    Add Product
                </h1>

                <p class="header-subtitle">
                    Add a hardware or construction material to your inventory.
                </p>

            </div>


            <a
                href="{{ route('products.index') }}"
                class="back-button"
            >
                ← Back to Products
            </a>


        </div>


        <div class="form-card">


            <div class="form-heading">

                <h2>
                    Product Information
                </h2>

                <p>
                    Enter the product details below.
                </p>

            </div>

            @if($errors->any())

                <div class="error-summary">

                    <strong>
                        Please correct the following errors:
                    </strong>


                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                action="{{ route('products.store') }}"
                method="POST"
            >

                @csrf


                <div class="form-grid">


                    <div class="form-group">

                        <label for="name">
                            Product Name
                            <span class="required">*</span>
                        </label>


                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Example: Portland Cement"
                            class="@error('name') input-error @enderror"
                            required
                        >


                        @error('name')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="sku">
                            SKU
                            <span class="required">*</span>
                        </label>


                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            value="{{ old('sku') }}"
                            placeholder="Example: CEM-001"
                            class="@error('sku') input-error @enderror"
                            required
                        >


                        <div class="helper-text">
                            Use a unique stock keeping unit.
                        </div>


                        @error('sku')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group full-width">

                        <label for="description">
                            Description
                        </label>


                        <textarea
                            id="description"
                            name="description"
                            placeholder="Enter product description"
                            class="@error('description') input-error @enderror"
                        >{{ old('description') }}</textarea>


                        @error('description')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="category">
                            Category
                            <span class="required">*</span>
                        </label>


                        <select
                            id="category"
                            name="category"
                            class="@error('category') input-error @enderror"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>


                            <option
                                value="Lumber"
                                {{ old('category') == 'Lumber' ? 'selected' : '' }}
                            >
                                Lumber
                            </option>


                            <option
                                value="Hardware"
                                {{ old('category') == 'Hardware' ? 'selected' : '' }}
                            >
                                Hardware
                            </option>


                            <option
                                value="Tools"
                                {{ old('category') == 'Tools' ? 'selected' : '' }}
                            >
                                Tools
                            </option>


                            <option
                                value="Electrical"
                                {{ old('category') == 'Electrical' ? 'selected' : '' }}
                            >
                                Electrical
                            </option>


                            <option
                                value="Plumbing"
                                {{ old('category') == 'Plumbing' ? 'selected' : '' }}
                            >
                                Plumbing
                            </option>


                            <option
                                value="Paint"
                                {{ old('category') == 'Paint' ? 'selected' : '' }}
                            >
                                Paint
                            </option>

                        </select>


                        @error('category')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    
<div class="form-group">
    <label for="supplier">Supplier</label>

    <select name="supplier" id="supplier">
        <option value="">Select Supplier</option>
        <option value="ABC Hardware Supply">ABC Hardware Supply</option>
        <option value="Prime Lumber Trading">Prime Lumber Trading</option>
        <option value="Color House Supplies">Color House Supplies</option>
        <option value="ABC Plumbing Center">ABC Plumbing Center</option>
        <option value="BuildRight Supplies">BuildRight Supplies</option>
    </select>

    @error('supplier')
        <div class="field-error">
            {{ $message }}
        </div>
    @enderror
</div>



                        @error('supplier')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="quantity">
                            Quantity
                            <span class="required">*</span>
                        </label>


                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity', 0) }}"
                            min="0"
                            required
                            class="@error('quantity') input-error @enderror"
                        >


                        @error('quantity')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="reorder_level">
                            Reorder Level
                            <span class="required">*</span>
                        </label>


                        <input
                            type="number"
                            id="reorder_level"
                            name="reorder_level"
                            value="{{ old('reorder_level', 10) }}"
                            min="0"
                            required
                            class="@error('reorder_level') input-error @enderror"
                        >


                        <div class="helper-text">
                            Low-stock alert starts at this quantity.
                        </div>


                        @error('reorder_level')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="unit_price">
                            Unit Price (₱)
                            <span class="required">*</span>
                        </label>


                        <input
                            type="number"
                            id="unit_price"
                            name="unit_price"
                            value="{{ old('unit_price') }}"
                            min="0"
                            step="0.01"
                            placeholder="Example: 250.00"
                            required
                            class="@error('unit_price') input-error @enderror"
                        >


                        @error('unit_price')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>


                <!-- BUTTONS -->

                <div class="buttons">


                    <a
                        href="{{ route('products.index') }}"
                        class="btn cancel-button"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn save-button"
                    >
                        Save Product
                    </button>


                </div>


            </form>


        </div>

        <div class="info-card">

            <h3 class="info-title">
                Hardware Inventory
            </h3>

            <p class="info-text">
                Categories available: Lumber, Hardware, Tools,
                Electrical, Plumbing, and Paint. Use accurate
                quantities and prices to keep inventory records updated.
            </p>

        </div>


    </div>


</body>

</html>

