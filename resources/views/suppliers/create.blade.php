<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier | Inventory Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { margin:0; padding:30px 18px; background:#FFF7F8; color:#4A3B47; font-family:Arial,sans-serif; }
        .container { max-width:900px; margin:auto; }
        .eyebrow { color:#C06C84; font-size:12px; font-weight:bold; letter-spacing:1.5px; text-transform:uppercase; }
        h1 { margin:8px 0; font-size:30px; }
        .muted { color:#887987; font-size:14px; }
        .card { margin-top:25px; padding:28px; background:white; border:1px solid #F3DCE5; border-radius:18px; box-shadow:0 4px 15px #8e5a8310; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        label { display:block; margin-bottom:8px; font-size:13px; font-weight:bold; }
        input, select, textarea { width:100%; padding:12px 14px; border:1px solid #E8D5DF; border-radius:10px; outline:none; background:white; color:#4A3B47; }
        input:focus, select:focus, textarea:focus { border-color:#C06C84; box-shadow:0 0 0 3px #FCE4E8; }
        textarea { min-height:95px; resize:vertical; }
        .field { margin-bottom:20px; }
        .full { grid-column:1/-1; }
        .actions { display:flex; justify-content:flex-end; gap:12px; margin-top:12px; }
        .btn { display:inline-block; padding:12px 20px; border:0; border-radius:10px; font-size:13px; font-weight:bold; text-decoration:none; cursor:pointer; }
        .primary { background:#8E5A83; color:white; }
        .primary:hover { background:#75476B; }
        .secondary { background:white; border:1px solid #E8D5DF; color:#6B5264; }
        .error { margin-top:6px; color:#D34B5B; font-size:12px; }
        .alert { margin-top:20px; padding:14px; background:#FFF0F1; border:1px solid #F5C8CF; color:#B42335; border-radius:10px; }
        .required { color:#C06C84; }
        @media(max-width:650px) { .grid { grid-template-columns:1fr; } .full { grid-column:auto; } .card { padding:20px; } }
    </style>
</head>
<body>
<div class="container">
    <div class="eyebrow">Inventory Management</div>
    <h1>Add New Supplier</h1>
    <p class="muted">Register a new supplier and provide their business and delivery information.</p>

    @if($errors->any())
        <div class="alert">
            <strong>Please check the following:</strong>
            <ul style="margin:8px 0 0 18px; list-style:disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="card" method="POST" action="{{ route('suppliers.store') }}">
        @csrf

        <div class="grid">
            <div class="field full">
                <label for="company_name">Company Name <span class="required">*</span></label>
                <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') }}" placeholder="Enter company name">
                @error('company_name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="contact_person">Contact Person <span class="required">*</span></label>
                <input id="contact_person" name="contact_person" type="text" required value="{{ old('contact_person') }}" placeholder="Full name">
                @error('contact_person') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="supplier@example.com">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="phone">Phone Number <span class="required">*</span></label>
                <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="Enter phone number">
                @error('phone') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="status">Supplier Status <span class="required">*</span></label>
                <select id="status" name="status" required>
                    <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                </select>
                @error('status') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field full">
                <label for="address">Business Address <span class="required">*</span></label>
                <textarea id="address" name="address" required placeholder="Enter complete business address">{{ old('address') }}</textarea>
                @error('address') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="payment_terms">Payment Terms <span class="required">*</span></label>
                <select id="payment_terms" name="payment_terms" required>
                    <option value="">Select payment terms</option>
                    <option value="Net 30" @selected(old('payment_terms') === 'Net 30')>Net 30</option>
                    <option value="COD" @selected(old('payment_terms') === 'COD')>COD (Cash on Delivery)</option>
                </select>
                @error('payment_terms') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="lead_time_days">Lead Time (Days)</label>
                <input id="lead_time_days" name="lead_time_days" type="number" min="0" value="{{ old('lead_time_days', 7) }}">
                @error('lead_time_days') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field full">
                <label for="minimum_order_amount">Minimum Order Amount (₱)</label>
                <input id="minimum_order_amount" name="minimum_order_amount" type="number" min="0" step="0.01" value="{{ old('minimum_order_amount', '0.00') }}">
                @error('minimum_order_amount') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('suppliers.index') }}" class="btn secondary">Cancel</a>
            <button type="submit" class="btn primary">Save Supplier</button>
        </div>
    </form>
</div>
</body>
</html>