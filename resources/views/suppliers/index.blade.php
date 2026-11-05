<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #FFF7F8;
            color: #4A3B47;
        }
        .page { max-width: 1500px; margin: auto; padding: 35px; }
        .top { display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:28px; }
        .eyebrow { color:#C06C84; font-size:12px; font-weight:bold; letter-spacing:1.5px; text-transform:uppercase; }
        h1 { margin:7px 0; font-size:30px; }
        .muted { color:#887987; font-size:14px; }
        .btn { display:inline-block; border:0; border-radius:10px; padding:12px 18px; text-decoration:none; font-size:13px; font-weight:bold; cursor:pointer; }
        .primary { background:#8E5A83; color:white; }
        .primary:hover { background:#75476B; }
        .secondary { background:white; color:#6B5264; border:1px solid #E8D5DF; }
        .cards { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:22px; }
        .card { background:white; border:1px solid #F3DCE5; border-radius:16px; padding:22px; box-shadow:0 3px 12px #8e5a8310; }
        .number { font-size:30px; font-weight:bold; margin-top:12px; }
        .filters { display:grid; grid-template-columns:2fr 1fr auto; gap:15px; align-items:end; }
        label { display:block; font-size:13px; font-weight:bold; margin-bottom:8px; }
        input, select { width:100%; border:1px solid #E8D5DF; border-radius:10px; padding:12px; background:white; color:#4A3B47; }
        .filter-actions { display:flex; gap:8px; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:900px; }
        th { background:#FFF7F8; color:#8E5A83; text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:.5px; }
        th, td { padding:17px 15px; border-bottom:1px solid #F3E8ED; font-size:13px; }
        tr:hover td { background:#FFFAFB; }
        .company { font-weight:bold; }
        .small { display:block; margin-top:5px; color:#9B8B98; font-size:11px; }
        .status { display:inline-block; padding:6px 11px; border-radius:30px; font-size:11px; font-weight:bold; }
        .active { background:#E3F7E9; color:#267343; }
        .inactive { background:#F0EDF0; color:#6B626B; }
        .count { background:#FCE4E8; color:#8E5A83; padding:6px 11px; border-radius:8px; font-weight:bold; }
        .actions { display:flex; gap:12px; align-items:center; }
        .actions a, .actions button { background:none; border:0; padding:0; font-weight:bold; font-size:12px; cursor:pointer; text-decoration:none; }
        .view { color:#8E5A83; }
        .edit { color:#C06C84; }
        .delete { color:#D34B5B; }
        .alert { padding:14px 18px; border-radius:10px; margin-bottom:20px; background:#E9F8EE; color:#267343; border:1px solid #BDE8CA; }
        .empty { text-align:center; padding:45px; color:#887987; }
        .pagination { padding:18px; }
        @media(max-width:750px) {
            .page { padding:18px; }
            .top { align-items:flex-start; flex-direction:column; }
            .cards { grid-template-columns:1fr; }
            .filters { grid-template-columns:1fr; }
            .filter-actions { align-items:center; }
        }
    </style>
</head>
<body>
<div class="page">

    <div class="top">
    <div>
        <h1>Supplier Management</h1>
        <p class="muted">Manage your suppliers, contact information, and delivery terms.</p>

        <a href="{{ route('dashboard') }}"
           style="display:inline-block; margin-top:15px; color:#8E5A83; font-size:13px; font-weight:bold; text-decoration:none;">
            ← Back to Dashboard
        </a>
    </div>

    <a href="{{ route('suppliers.create') }}" class="btn primary">
        ＋ Add New Supplier
    </a>
</div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="cards">
        <div class="card">
            <div class="muted">Total Suppliers</div>
            <div class="number">{{ $suppliers->total() }}</div>
        </div>
        <div class="card">
            <div class="muted">Current Page</div>
            <div class="number">{{ $suppliers->count() }}</div>
        </div>
        <div class="card">
            <div class="muted">Showing Page</div>
            <div class="number">{{ $suppliers->currentPage() }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:22px">
        <form method="GET" action="{{ route('suppliers.index') }}" class="filters">
            <div>
                <label>Search Suppliers</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Company, contact person, email, or phone...">
            </div>
            <div>
                <label>Supplier Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn primary">Search</button>
                <a href="{{ route('suppliers.index') }}" class="btn secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card" style="padding:0; overflow:hidden">
        <div style="padding:22px; border-bottom:1px solid #F3DCE5; display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap">
            <div>
                <h2 style="font-size:19px; font-weight:bold">Supplier Directory</h2>
                <p class="muted" style="margin-top:5px">Registered suppliers and their business information.</p>
            </div>
            <span class="muted">{{ $suppliers->total() }} supplier(s) found</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Contact Person</th>
                        <th>Phone / Email</th>
                        <th>Lead Time</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td>
                            <span class="company">{{ $supplier->company_name }}</span>
                            <span class="small">{{ $supplier->payment_terms ?? 'Not provided' }}</span>
                        </td>
                        <td>{{ $supplier->contact_person ?: 'Not Provided' }}</td>
                        <td>
                            {{ $supplier->phone ?: 'No phone' }}
                            <span class="small">{{ $supplier->email ?: 'No email' }}</span>
                        </td>
                        <td>{{ $supplier->lead_time_days ?? 0 }} days</td>
                        <td><span class="count">{{ $supplier->products_count }}</span></td>
                        <td>
                            @if($supplier->status === 'active')
                                <span class="status active">Active</span>
                            @else
                                <span class="status inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a class="view" href="{{ route('suppliers.show', $supplier) }}">View</a>
                                <a class="edit" href="{{ route('suppliers.edit', $supplier) }}">Edit</a>
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <div style="font-size:38px">🏢</div>
                                <strong>No suppliers found</strong>
                                <p style="margin-top:6px">Try changing your search or add a new supplier.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="pagination">{{ $suppliers->links() }}</div>
        @endif
    </div>

    <p class="muted" style="text-align:center; margin-top:22px; font-size:11px">
        Inventory Management System · Supplier Management
    </p>
</div>
</body>
</html>