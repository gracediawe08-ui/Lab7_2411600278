<x-app-layout>
<style>
    :root {
        --topbar-height: 64px;
        --sidebar-width: 245px;
        --mauve: #8E5A83;
        --pink: #E5989B;
        --light-pink: #FFF7F8;
        --text: #4A3B47;
        --muted: #9A8B95;
        --border: #F1E5EA;
    }

     * { box-sizing: border-box; }
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        min-height: 100%;
        background: var(--light-pink) !important;
        color: var(--text);
        font-family: Arial, Helvetica, sans-serif;
    }
    body { overflow-x: hidden; }

    

body > div:first-child > header,
body > div:first-child > nav,
.inventory-topbar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    height: 64px !important;
    min-height: 64px !important;
    margin: 0 !important;
    z-index: 99999 !important;
    background: #FFFFFF !important;
    transform: none !important;
}

/* Keep dashboard content below the topbar */
.inventory-sidebar {
    top: 64px !important;
    height: calc(100vh - 64px) !important;
}

.inventory-main {
    margin-top: 64px !important;
}



    .inventory-sidebar {
        position: fixed;
        top: var(--topbar-height);
        left: 0;
        bottom: 0;
        width: var(--sidebar-width);
        padding: 0;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background: var(--mauve);
        color: #fff;
        z-index: 40;
    }
    .inventory-sidebar .brand {
        min-height: 115px;
        padding: 20px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .brand-title { font-size: 19px; line-height: 1.25; font-weight: 800; }
    .brand-subtitle { margin-top: 7px; font-size: 10px; opacity: .85; }
    .sidebar-line { height: 1px; margin: 0 16px 12px; background: rgba(255,255,255,.25); }
    .sidebar-label { padding: 8px 12px; text-align: center; font-size: 10px; letter-spacing: 1px; font-weight: 800; }
    .sidebar-menu { display: flex; flex-direction: column; gap: 8px; padding: 12px; }
    .sidebar-menu a, .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        min-height: 44px;
        padding: 12px 14px;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s ease;
    }
    .sidebar-menu a:hover, .sidebar-link:hover, .sidebar-link.active {
        background: #F3A6B2;
        color: var(--text);
    }
.sidebar-icon { width: 18px; text-align: center; font-size: 16px; }
    .sidebar-logout { margin-top: auto; padding: 12px; }
    .logout-button {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 10px;
        background: transparent;
        color: #fff;
        text-align: left;
        cursor: pointer;
        font-weight: 700;
    }
    .logout-button:hover { background: rgba(255,255,255,.12); }
.inventory-main {
        position: relative;
        margin-left: var(--sidebar-width);
        margin-top: var(--topbar-height);
        min-height: calc(100vh - var(--topbar-height));
        padding: 25px 28px 35px;
        background: var(--light-pink);
    }
    .main-container { width: 100%; max-width: 1450px; min-width: 0; margin: 0 auto; }
    .topbar { display: flex; justify-content: space-between; align-items: center; gap: 18px; margin-bottom: 25px; }
    .page-title { margin: 0; font-size: 27px; font-weight: 800; color: var(--text); }
    .page-description { margin: 6px 0 0; color: #8A7B86; font-size: 13px; line-height: 1.5; }
    .add-button {
        display: inline-block; flex-shrink: 0; padding: 12px 17px; border-radius: 11px;
        background: linear-gradient(135deg, var(--mauve), var(--pink)); color: #fff;
        text-decoration: none; font-size: 12px; font-weight: 800;
        box-shadow: 0 6px 15px rgba(142,90,131,.18);
    }
    .add-button:hover { color: #fff; opacity: .9; }

    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 22px; }
    .stat-card {
        position: relative; min-width: 0; overflow: hidden; padding: 20px;
        background: #fff; border: 1px solid var(--border); border-radius: 17px;
        box-shadow: 0 6px 20px rgba(74,59,71,.055);
    }

    .stat-card::after { content: ""; position: absolute; right: -25px; bottom: -25px; width: 85px; height: 85px; border-radius: 50%; background: #FCE4E8; opacity: .65; pointer-events: none; }
    .stat-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .stat-label { color: var(--muted); font-size: 10px; font-weight: 800; letter-spacing: .4px; }
    .stat-icon { display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 40px; height: 40px; border-radius: 11px; font-size: 17px; }
    .purple-bg { background: #F2E8F0; color: var(--mauve); }
    .pink-bg { background: #FCE4E8; color: #C06C84; }
    .orange-bg { background: #FFF0E1; color: #C47732; }
    .green-bg { background: #EAF6EF; color: #4B8B67; }
    .stat-number { position: relative; z-index: 1; margin-top: 15px; color: var(--text); font-size: 27px; font-weight: 800; overflow-wrap: anywhere; }
    .stat-footer { margin-top: 5px; color: var(--muted); font-size: 10px; line-height: 1.5; }

    .dashboard-card { min-width: 0; background: #fff; border: 1px solid var(--border); border-radius: 18px; box-shadow: 0 6px 20px rgba(74,59,71,.055); }
    .card-padding { padding: 22px; }
    .card-title { margin: 0; color: var(--text); font-size: 17px; font-weight: 800; }
    .card-subtitle { margin: 5px 0 0; color: var(--muted); font-size: 11px; line-height: 1.5; }

    .analytics-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; margin-bottom: 22px; scroll-margin-top: 20px; }
    .analytics-grid .dashboard-card { min-width: 0; overflow: hidden; }
    .chart-area { position: relative; width: 100%; height: 270px; margin-top: 18px; }
    .chart-area canvas { display: block; max-width: 100%; }

    .middle-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr); gap: 18px; margin-bottom: 22px; align-items: stretch; }
    .middle-grid > .dashboard-card { min-width: 0; overflow: hidden; }
    .low-stock-list { margin-top: 12px; }
    .low-stock-item { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 11px 0; border-bottom: 1px solid #F2E9ED; }
    .low-stock-item:last-child { border-bottom: none; }
    .stock-product { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .product-avatar { display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #FCE4E8, #F2E8F0); color: var(--mauve); font-size: 17px; }
    .product-name-small { color: var(--text); font-size: 12px; font-weight: 700; overflow-wrap: anywhere; }
    .product-sku-small { margin-top: 3px; color: var(--muted); font-size: 9px; }
    .qty-warning { flex-shrink: 0; padding: 6px 9px; border-radius: 15px; background: #FFF0E1; color: #C47732; font-size: 10px; font-weight: 800; white-space: nowrap; }
    .empty-stock { padding: 30px 10px; text-align: center; color: #4B8B67; font-size: 11px; font-weight: 700; }

    .inventory-tools { margin-bottom: 22px; }
    .tools-top { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 17px; }
    .view-products { flex-shrink: 0; color: var(--mauve); text-decoration: none; font-size: 11px; font-weight: 800; }
    .view-products:hover { color: #C06C84; }
    .filters { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; }
    .filter-control { width: 100%; min-width: 0; padding: 11px 12px; border: 1px solid #E8DCE2; border-radius: 10px; background: #FFFCFD; color: var(--text); font-size: 12px; outline: none; }
    .filter-control:focus { border-color: var(--pink); box-shadow: 0 0 0 3px rgba(229,152,155,.10); }

    .table-card { margin-bottom: 22px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 14px; }
    .table-wrapper { width: 100%; overflow-x: auto; }
    .product-table { width: 100%; border-collapse: collapse; }
    .product-table th { padding: 12px 10px; border-bottom: 1px solid #EEE4E9; color: var(--muted); text-align: left; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; white-space: nowrap; }
    .product-table td { padding: 13px 10px; border-bottom: 1px solid #F4ECEF; color: var(--text); font-size: 11px; }
    .product-table tbody tr:hover td { background: #FFFAFC; }
    .strong-text { font-weight: 800; }
    .muted-text { color: #93848F !important; }
    .status-badge { display: inline-block; padding: 5px 8px; border-radius: 14px; font-size: 9px; font-weight: 800; white-space: nowrap; }
    .status-in { background: #EAF6EF; color: #43815E; }
    .status-low { background: #FFF0E1; color: #C47732; }
    .status-out { background: #FCE7E9; color: #C45C67; }
    .empty-row { padding: 28px !important; color: var(--muted) !important; text-align: center; }

    .quick-card { margin-bottom: 22px; padding: 22px; border-radius: 18px; color: #fff; background: linear-gradient(135deg, var(--mauve), #C06C84, var(--pink)); box-shadow: 0 9px 22px rgba(142,90,131,.15); }
    .quick-card .card-title, .quick-card .card-subtitle { color: #fff; }
    .quick-actions { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-top: 17px; }
    .quick-link { display: block; min-width: 0; padding: 14px 8px; border: 1px solid rgba(255,255,255,.2); border-radius: 12px; background: rgba(255,255,255,.11); color: #fff; text-decoration: none; text-align: center; font-size: 10px; font-weight: 800; }
    .quick-link:hover { background: rgba(255,255,255,.2); color: #fff; }
    .quick-link-icon { display: block; margin-bottom: 6px; font-size: 19px; }

    #reports { margin-bottom: 10px; scroll-margin-top: 20px; }
    .report-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
    .report-box { min-width: 0; padding: 15px; border: 1px solid #F0E3E8; border-radius: 12px; background: #FFFAFC; }
    .report-label { color: var(--muted); font-size: 10px; }
    .report-value { margin-top: 5px; color: var(--mauve); font-size: 21px; font-weight: 800; overflow-wrap: anywhere; }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .inventory-main { padding: 22px 18px 30px; }
    }
    @media (max-width: 800px) {
        :root { --sidebar-width: 190px; }
        .middle-grid, .analytics-grid { grid-template-columns: 1fr; }
        .filters { grid-template-columns: 1fr; }
        .quick-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 560px) {
        :root { --sidebar-width: 0px; }
        .inventory-sidebar { display: none; }
        .inventory-main { margin-left: 0; padding: 18px 12px 25px; }
        .topbar { align-items: flex-start; flex-direction: column; }
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-card { padding: 14px; }
        .stat-number { font-size: 22px; }
        .card-padding { padding: 16px; }
        .report-grid { grid-template-columns: 1fr; }
    }

.stat-card-link {
    display: block;
    color: inherit;
    text-decoration: none;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(74, 59, 71, 0.12);
}

.stat-card-link:focus-visible {
    outline: 2px solid #8E5A83;
    outline-offset: 3px;
}


.topbar-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.notification-wrapper {
    position: relative;
}

.notification-bell {
    position: relative;
    width: 42px;
    height: 42px;
    border: 1px solid #eadde6;
    border-radius: 12px;
    background: #fff;
    cursor: pointer;
}

.notification-bell:hover {
    background: #fff7f8;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 19px;
    height: 19px;
    padding: 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    background: #dc3545;
    color: white;
    font-size: 11px;
    font-weight: bold;
}

.notification-dropdown {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    width: 330px;
    max-width: calc(100vw - 32px);
    max-height: 420px;
    overflow-y: auto;
    background: white;
    border: 1px solid #eadde6;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(74, 59, 71, .15);
    z-index: 1000;
}

.notification-dropdown.show {
    display: block;
}

.notification-heading {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #f0e7ed;
    color: #4A3B47;
}

.notification-item {
    padding: 14px 15px;
    border-bottom: 1px solid #f5edf2;
}

.notification-item.unread {
    background: #fff7f8;
}

.notification-item-title {
    font-weight: bold;
    color: #4A3B47;
    margin-bottom: 5px;
}

.notification-item-message {
    font-size: 13px;
    color: #665965;
    overflow-wrap: anywhere;
}

.notification-item small {
    display: block;
    margin-top: 7px;
    color: #847581;
}

.notification-read-button {
    margin-top: 8px;
    border: none;
    background: transparent;
    color: #8E5A83;
    cursor: pointer;
}

.notification-empty {
    padding: 22px 15px;
    text-align: center;
    color: #847581;
}

.notification-wrapper {
    position: relative;
    z-index: 1001;
}

.notification-bell {
    position: relative;
    z-index: 1002;
    pointer-events: auto;
    cursor: pointer;
}

.notification-dropdown {
    z-index: 1003;
}



</style>

<div class="inventory-app">
    <aside class="inventory-sidebar">
        <div class="brand">
            <div class="brand-title">Hardware<br>Inventory System</div>
            <div class="brand-subtitle">Construction Materials</div>
        </div>
        <div class="sidebar-line"></div>
        <div class="sidebar-label">MAIN MENU</div>
        <nav class="sidebar-menu">
    <a href="{{ route('dashboard') }}"
       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sidebar-icon">⌂</span>Dashboard
    </a>

    <a href="{{ route('products.index') }}"
       class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
        <span class="sidebar-icon">▣</span>Products
    </a>

    <a href="{{ route('products.create') }}"
       class="sidebar-link">
        <span class="sidebar-icon">＋</span>Add Product
    </a>

    <a href="{{ route('inventory.index') }}"
       class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
        <span class="sidebar-icon">▤</span>Inventory
    </a>

    <a href="{{ route('suppliers.index') }}"
       class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
        <span class="sidebar-icon">🏢</span>Suppliers
    </a>

    <a href="{{ route('purchase-orders.index') }}"
   class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
    <span class="sidebar-icon">📑</span>Purchase Orders
    </a>

    <a href="{{ route('dashboard') }}#analytics" class="sidebar-link">
        <span class="sidebar-icon">◔</span>Analytics
    </a>

    <a href="{{ route('dashboard') }}#reports" class="sidebar-link">
        <span class="sidebar-icon">▥</span>Reports
    </a>
</nav>
        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">↪ Logout</button>
            </form>
        </div>
    </aside>

      <main class="inventory-main">
        <div class="main-container">
           
<header class="topbar">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-description">
            Welcome back, Admin! Here's your hardware inventory overview.
        </p>
    </div>

    <div class="topbar-actions">
        <div class="notification-wrapper">
            <button type="button"
                    class="notification-bell"
                    id="notificationBell"
                    aria-label="Notifications"
                    aria-expanded="false">
                <span style="font-size: 22px;">&#128276;</span>

                @php
                    $unreadCount = auth()->user()
                        ->unreadNotifications()
                        ->count();
                @endphp

                @if($unreadCount > 0)
                    <span class="notification-badge">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-heading">
                    <strong>Notifications</strong>
                    <span>{{ $unreadCount }} unread</span>
                </div>

                @forelse(auth()->user()->notifications()->latest()->get() as $notification)
                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                        <div class="notification-item-title">
                            {{ $notification->data['title'] ?? 'Inventory Alert' }}
                        </div>

                        <div class="notification-item-message">
                            {{ $notification->data['message']
                                ?? $notification->data['body']
                                ?? 'Please check your inventory.' }}
                        </div>

                        <small>{{ $notification->created_at->diffForHumans() }}</small>

                        @if(!$notification->read_at)
                            <form method="POST"
                                  action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="notification-read-button">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="notification-empty">No notifications yet.</div>
                @endforelse
            </div>
        </div>

        <a href="{{ route('products.create') }}" class="add-button">
            + Add Product
        </a>
    </div>
</header>


            
<section class="stats-grid">
   
<a href="{{ route('products.index') }}" class="stat-card stat-card-link">
    <div class="stat-head">
        <div class="stat-label">TOTAL PRODUCTS</div>
        <div class="stat-icon purple-bg">📦</div>
    </div>
    <div class="stat-number" id="statTotalProducts">{{ $totalProducts }}</div>
    <div class="stat-footer">Products in inventory</div>
</a>

<a href="{{ route('inventory.index') }}" class="stat-card stat-card-link">
    <div class="stat-head">
        <div class="stat-label">TOTAL STOCK QUANTITY</div>
        <div class="stat-icon pink-bg">📋</div>
    </div>
    <div class="stat-number" id="statTotalStockQuantity">{{ number_format($products->sum('quantity')) }}</div>
    <div class="stat-footer">Total units in inventory</div>
</a>

<a href="{{ route('products.index') }}" class="stat-card stat-card-link">
    <div class="stat-head">
        <div class="stat-label">INVENTORY VALUE</div>
        <div class="stat-icon green-bg">₱</div>
    </div>
    <div class="stat-number" id="statInventoryValue">₱{{ number_format($inventoryValue, 2) }}</div>
    <div class="stat-footer">Total current stock value</div>
</a>

<a href="{{ route('products.index') }}?filter=low-stock" class="stat-card stat-card-link">
    <div class="stat-head">
        <div class="stat-label">LOW STOCK ITEMS</div>
        <div class="stat-icon orange-bg">⚠️</div>
    </div>
    <div class="stat-number" id="statLowStock">{{ $lowStock }}</div>
    <div class="stat-footer">Products needing replenishment</div>
</a>
</section>


<section class="stats-grid" style="margin-top: 22px;">

    <a href="{{ route('purchase-orders.index') }}" class="stat-card stat-card-link">
        <div class="stat-head">
            <div class="stat-label">TOTAL PURCHASE ORDERS</div>
            <div class="stat-icon purple-bg">📑</div>
        </div>
        <div class="stat-number">{{ $totalPurchaseOrders }}</div>
        <div class="stat-footer">All purchase orders</div>
    </a>

    <a href="{{ route('purchase-orders.index', ['status' => 'pending_approval']) }}"
       class="stat-card stat-card-link">
        <div class="stat-head">
            <div class="stat-label">PENDING APPROVAL</div>
            <div class="stat-icon orange-bg">⏳</div>
        </div>
        <div class="stat-number">{{ $pendingPurchaseOrders }}</div>
        <div class="stat-footer">Orders awaiting approval</div>
    </a>

    <a href="{{ route('purchase-orders.index', ['status' => 'approved']) }}"
       class="stat-card stat-card-link">
        <div class="stat-head">
            <div class="stat-label">APPROVED ORDERS</div>
            <div class="stat-icon pink-bg">✓</div>
        </div>
        <div class="stat-number">{{ $approvedPurchaseOrders }}</div>
        <div class="stat-footer">Orders approved for sending</div>
    </a>

    <a href="{{ route('purchase-orders.index', ['status' => 'received']) }}"
       class="stat-card stat-card-link">
        <div class="stat-head">
            <div class="stat-label">RECEIVED ORDERS</div>
            <div class="stat-icon green-bg">📦</div>
        </div>
        <div class="stat-number">{{ $receivedPurchaseOrders }}</div>
        <div class="stat-footer">Completed deliveries</div>
    </a>

</section>

<section class="dashboard-card card-padding table-card">

    <div class="table-header">
        <div>
            <h2 class="card-title">Recent Purchase Orders</h2>
            <p class="card-subtitle">
                Latest supplier orders and their current status
            </p>
        </div>

        <a href="{{ route('purchase-orders.index') }}"
           class="view-products">
            View All Orders →
        </a>
    </div>

    <div style="margin-bottom: 15px;">
        <a href="{{ route('purchase-orders.create') }}"
           class="add-button">
            + Create Purchase Order
        </a>
    </div>

    <div class="table-wrapper">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
               @forelse($widgetRecentOrders as $order)
                    <tr>
                        <td class="strong-text">
                            {{ $order->order_number }}
                        </td>

                        <td>
                            {{ $order->supplier->company_name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $order->order_date?->format('M d, Y') ?? 'N/A' }}
                        </td>

                        <td class="strong-text">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </td>

                        <td>
                            @php
                                $orderStatusClass = match($order->status) {
                                    'received' => 'status-in',
                                    'approved', 'sent' => 'status-in',
                                    'pending_approval' => 'status-low',
                                    'rejected' => 'status-out',
                                    default => 'status-badge'
                                };
                            @endphp

                            <span class="status-badge {{ $orderStatusClass }}">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('purchase-orders.show', $order) }}"
                               class="view-products">
                                View Details →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">
                            No purchase orders found.
                            Create your first purchase order.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="analytics-grid" id="procurement-widgets">

    <div class="dashboard-card card-padding">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
            <div>
                <h2 class="card-title">Pending Orders</h2>
                <p class="card-subtitle">Purchase orders awaiting approval</p>
            </div>
            <span style="background:#FCE4E8;color:#8E5A83;padding:7px 12px;border-radius:20px;font-weight:700;">
                {{ $widgetPendingOrders->count() }}
            </span>
        </div>

        @forelse($widgetPendingOrders as $order)
            <div style="padding:13px 0;border-bottom:1px solid #F3E5EB;">
                <div style="display:flex;justify-content:space-between;gap:10px;">
                    <strong>{{ $order->order_number }}</strong>
                    <span style="color:#C06C84;font-weight:700;">
                        ₱{{ number_format($order->total_amount, 2) }}
                    </span>
                </div>
                <small style="color:#8B7885;">
                    {{ $order->supplier->company_name ?? 'No supplier' }}
                </small>
            </div>
        @empty
            <p style="padding:20px 0;color:#8B7885;text-align:center;">
                No orders waiting for approval.
            </p>
        @endforelse

        <a href="{{ route('purchase-orders.index', ['status' => 'pending_approval']) }}"
           style="display:inline-block;margin-top:15px;color:#8E5A83;font-weight:700;text-decoration:none;">
            View Pending Orders →
        </a>
    </div>

    <div class="dashboard-card card-padding">
        <h2 class="card-title">Supplier Performance</h2>
        <p class="card-subtitle">Supplier order activity and purchase value</p>

        @forelse($widgetSuppliers as $supplier)
            <div style="padding:12px 0;border-bottom:1px solid #F3E5EB;">
                <strong>{{ $supplier->company_name }}</strong>

                <div style="display:flex;justify-content:space-between;gap:10px;margin-top:6px;">
                    <small style="color:#8B7885;">
                        {{ $supplier->purchase_orders_count }} orders
                    </small>
                    <strong style="color:#8E5A83;">
                        ₱{{ number_format($supplier->purchase_orders_sum_total_amount ?? 0, 2) }}
                    </strong>
                </div>
            </div>
        @empty
            <p style="padding:20px 0;color:#8B7885;text-align:center;">
                No supplier records found.
            </p>
        @endforelse

        <a href="{{ route('suppliers.performance') }}"
           style="display:inline-block;margin-top:15px;color:#8E5A83;font-weight:700;text-decoration:none;">
            View Full Supplier Report →
        </a>
    </div>

    <div class="dashboard-card card-padding">
        <h2 class="card-title">Reorder Alerts</h2>
        <p class="card-subtitle">Products that need inventory replenishment</p>

        @forelse($widgetReorderAlerts as $product)
            <div style="padding:12px 0;border-bottom:1px solid #F3E5EB;">
                <strong>{{ $product->name }}</strong>

                <div style="display:flex;justify-content:space-between;gap:10px;margin-top:6px;">
                    <small style="color:#C06C84;">
                        Stock: {{ $product->quantity }}
                    </small>
                    <small style="color:#8B7885;">
                        Reorder Point:
                        {{ $product->reorder_analysis['effective_reorder_point'] ?? $product->reorder_level }}
                    </small>
                </div>

                <div style="margin-top:5px;font-size:12px;color:#8B7885;">
                    Suggested Order:
                    <strong style="color:#8E5A83;">
                        {{ $product->reorder_analysis['suggested_order_quantity'] ?? 0 }} units
                    </strong>
                    | {{ $product->supplierRecord->company_name ?? 'No supplier' }}
                </div>
            </div>
        @empty
            <p style="padding:20px 0;color:#8B7885;text-align:center;">
                No products currently need replenishment.
            </p>
        @endforelse

        <a href="{{ route('inventory.index') }}"
           style="display:inline-block;margin-top:15px;color:#8E5A83;font-weight:700;text-decoration:none;">
            View Inventory →
        </a>
    </div>

</section>

             <section class="analytics-grid" id="analytics">
                <div class="dashboard-card card-padding">
                    <h2 class="card-title">Inventory by Category</h2>
                    <p class="card-subtitle">Product quantities across your hardware categories</p>
                    <div class="chart-area"><canvas id="categoryChart"></canvas></div>
                </div>
                <div class="dashboard-card card-padding">
                    <h2 class="card-title">Inventory Value</h2>
                    <p class="card-subtitle">Total value of your inventory</p>
                    <div class="chart-area"><canvas id="valueChart"></canvas></div>
                </div>
            </section>

           
            <section class="middle-grid">
                <div class="dashboard-card card-padding">
                    <h2 class="card-title">Low Stock Alert</h2>
                    <p class="card-subtitle">Products that need attention</p>
                    <div class="low-stock-list">
                        @forelse($lowStockProducts as $product)
                            @php
                                $name = strtolower($product->name);
                                if (str_contains($name, 'cement')) {
                                    $icon = '🧱';
                                } elseif (str_contains($name, 'plywood') || str_contains($name, 'lumber')) {
                                    $icon = '🪵';
                                } elseif (str_contains($name, 'hammer') || str_contains($name, 'screwdriver') || str_contains($name, 'wrench')) {
                                    $icon = '🔨';
                                } elseif (str_contains($name, 'wire') || str_contains($name, 'electrical')) {
                                    $icon = '⚡';
                                } elseif (str_contains($name, 'bulb')) {
                                    $icon = '💡';
                                } elseif (str_contains($name, 'pipe') || str_contains($name, 'faucet') || str_contains($name, 'elbow')) {
                                    $icon = '🚰';
                                } elseif (str_contains($name, 'paint')) {
                                    $icon = '🎨';
                                } elseif (str_contains($name, 'steel') || str_contains($name, 'nail')) {
                                    $icon = '🔩';
                                } else {
                                    $icon = '📦';
                                }
                            @endphp
                            <div class="low-stock-item">
                                <div class="stock-product">
                                    <div class="product-avatar">{{ $icon }}</div>
                                    <div>
                                        <div class="product-name-small">{{ $product->name }}</div>
                                        <div class="product-sku-small">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                                <span class="qty-warning">{{ $product->quantity }} / {{ $product->reorder_level }}</span>
                            </div>
                        @empty
                            <div class="empty-stock">✓ No low-stock products at the moment.</div>
                        @endforelse
                    </div>
                </div>

                <div class="dashboard-card card-padding">
                    <h2 class="card-title">Stock Status</h2>
                    <p class="card-subtitle">Overview of in-stock, low-stock, and unavailable products</p>
                    <div class="chart-area"><canvas id="stockChart"></canvas></div>
                </div>
            </section>

            <section class="dashboard-card card-padding inventory-tools">
                <div class="tools-top">
                    <div>
                        <h2 class="card-title">Inventory Management</h2>
                        <p class="card-subtitle">Search, filter and monitor your hardware products</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="view-products">View All →</a>
                </div>
                <div class="filters">
                    <input type="text" id="productSearch" class="filter-control" placeholder="🔍 Search product or SKU...">
                    <select id="categoryFilter" class="filter-control">
                        <option value="">All Categories</option>
                        @foreach($categoryData as $category)
                            <option value="{{ strtolower($category->category) }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                    <select id="stockFilter" class="filter-control">
                        <option value="">All Stock Status</option>
                        <option value="in stock">In Stock</option>
                        <option value="low stock">Low Stock</option>
                        <option value="out of stock">Out of Stock</option>
                    </select>
                </div>
            </section>

            <section class="dashboard-card card-padding table-card">
                <div class="table-header">
                    <div>
                        <h2 class="card-title">Recent Products</h2>
                        <p class="card-subtitle">Latest hardware products added to inventory</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="view-products">Manage Products →</a>
                </div>
                <div class="table-wrapper">
                    <table class="product-table" id="productTable">
                        <thead><tr><th>Product</th><th>SKU</th><th>Category</th><th>Quantity</th><th>Unit Price</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($products as $product)
                            @if($product->quantity == 0)
                                @php($status = 'Out of Stock')
                                @php($statusClass = 'status-out')
                            @elseif($product->quantity <= $product->reorder_level)
                                @php($status = 'Low Stock')
                                @php($statusClass = 'status-low')
                            @else
                                @php($status = 'In Stock')
                                @php($statusClass = 'status-in')
                            @endif
                            <tr data-name="{{ strtolower($product->name) }}" data-sku="{{ strtolower($product->sku) }}" data-category="{{ strtolower($product->category) }}" data-status="{{ strtolower($status) }}">
                               

<td>
    <div class="recent-product-info">
        @if(str_contains(strtolower($product->name), 'cement'))
            <span class="recent-product-icon">🧱</span>
        @elseif(str_contains(strtolower($product->name), 'plywood'))
            <span class="recent-product-icon">🪵</span>
        @elseif(str_contains(strtolower($product->name), 'hammer')) 
            <span class="recent-product-icon">🔨</span>
        @elseif(str_contains(strtolower($product->name), 'screwdriver'))
            <span class="recent-product-icon">🪛</span>
        @elseif(str_contains(strtolower($product->name), 'wrench'))
            <span class="recent-product-icon">🔧</span>
        @elseif(str_contains(strtolower($product->name), 'wire'))
            <span class="recent-product-icon">⚡</span>
        @elseif(str_contains(strtolower($product->name), 'bulb'))
            <span class="recent-product-icon">💡</span>
        @elseif(str_contains(strtolower($product->name), 'pipe') || str_contains(strtolower($product->name), 'faucet') || str_contains(strtolower($product->name), 'elbow'))
            <span class="recent-product-icon">🚰</span>
        @elseif(str_contains(strtolower($product->name), 'paint'))
            <span class="recent-product-icon">🎨</span>
        @elseif(str_contains(strtolower($product->name), 'steel') || str_contains(strtolower($product->name), 'nail'))
            <span class="recent-product-icon">🔩</span>
        @else
            <span class="recent-product-icon">📦</span>
        @endif

        <span class="strong-text">{{ $product->name }}</span>
    </div>
</td>

    
                                <td class="muted-text">{{ $product->sku }}</td>
                                <td>{{ $product->category }}</td>
                                <td class="strong-text">{{ $product->quantity }}</td>
                                <td>₱{{ number_format($product->unit_price, 2) }}</td>
                                <td><span class="status-badge {{ $statusClass }}">{{ $status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-row">No products found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-card card-padding" id="reports">
                <h2 class="card-title">Inventory Reports</h2>
                <p class="card-subtitle">Quick summary of your hardware inventory</p>
                <div class="report-grid">
                    <div class="report-box"><div class="report-label">Total Products</div><div class="report-value">{{ $totalProducts }}</div></div>
                    <div class="report-box"><div class="report-label">Low Stock Items</div><div class="report-value">{{ $lowStock }}</div></div>
                    <div class="report-box"><div class="report-label">Out of Stock</div><div class="report-value">{{ $outOfStock }}</div></div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stockInCount = Number(@json($inStockCount));
    const lowStockCount = Number(@json($lowStock));
    const outOfStockCount = Number(@json($outOfStock));
    const categoryLabels = @json($categoryData->pluck('category')->values());
    const categoryQuantities = @json($categoryData->pluck('total_quantity')->values()).map(Number);
    const categoryValues = @json($categoryData->pluck('total_value')->values()).map(Number);

    const stockCanvas = document.getElementById('stockChart');
    if (stockCanvas) {
        new Chart(stockCanvas, {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [stockInCount, lowStockCount, outOfStockCount],
                    backgroundColor: ['#8E5A83', '#E5989B', '#C06C84'],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 16, font: { size: 11 } }
                    }
                }
            }
        });
    }

    const categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas) {
        new Chart(categoryCanvas, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Quantity',
                    data: categoryQuantities,
                    backgroundColor: '#8E5A83',
                    borderRadius: 7,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { font: { size: 10 } }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    const valueCanvas = document.getElementById('valueChart');
    if (valueCanvas) {
        new Chart(valueCanvas, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Inventory Value',
                    data: categoryValues,
                    backgroundColor: '#E5989B',
                    borderRadius: 7,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return '₱' + Number(context.raw).toLocaleString('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    x: { ticks: { font: { size: 10 } }, grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function (value) { return '₱' + Number(value).toLocaleString('en-PH'); } }
                    }
                }
            }
        });
    }

    const searchInput = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('stockFilter');
    const productTableBody = document.querySelector('#productTable tbody');
    let searchTimer = null;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getStatus(product) {
        const quantity = Number(product.quantity);
        const reorderLevel = Number(product.reorder_level);
        if (quantity === 0) return 'Out of Stock';
        if (quantity <= reorderLevel) return 'Low Stock';
        return 'In Stock';
    }

    function renderProducts(products) {
        if (!productTableBody) return;
        productTableBody.innerHTML = '';
        if (!Array.isArray(products) || products.length === 0) {
            productTableBody.innerHTML = '<tr><td colspan="6" class="empty-row">No matching products found.</td></tr>';
            return;
        }
        products.forEach(function (product) {
            const status = getStatus(product);
            const statusClass = status === 'Low Stock' ? 'status-low' : (status === 'Out of Stock' ? 'status-out' : 'status-in');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="strong-text">${escapeHtml(product.name)}</td>
                <td class="muted-text">${escapeHtml(product.sku)}</td>
                <td>${escapeHtml(product.category)}</td>
                <td class="strong-text">${escapeHtml(product.quantity)}</td>
                <td>₱${Number(product.unit_price).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td><span class="status-badge ${statusClass}">${status}</span></td>`;
            productTableBody.appendChild(row);
        });
    }

    function performSearch() {
        const params = new URLSearchParams();
        const search = searchInput ? searchInput.value.trim() : '';
        const category = categoryFilter ? categoryFilter.value : '';
        let status = '';
        if (stockFilter) {
            if (stockFilter.value === 'in stock') status = 'in_stock';
            else if (stockFilter.value === 'low stock') status = 'low_stock';
            else if (stockFilter.value === 'out of stock') status = 'out_of_stock';
        }
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        if (status) params.append('status', status);

        fetch(`{{ route('dashboard.search') }}?${params.toString()}`, {
            method: 'GET',
            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Search request failed.');
            return response.json();
        })
        .then(renderProducts)
        .catch(function (error) {
            console.error('Product search error:', error);
            if (productTableBody) productTableBody.innerHTML = '<tr><td colspan="6" class="empty-row">Unable to load search results. Please try again.</td></tr>';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(performSearch, 250);
        });
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                clearTimeout(searchTimer);
                performSearch();
            }
        });
    }
    
    if (categoryFilter) categoryFilter.addEventListener('change', performSearch);
    if (stockFilter) stockFilter.addEventListener('change', performSearch);

    const refreshUrl = @json(route('dashboard.refresh'));

    async function refreshDashboard() {
        try {
            const response = await fetch(refreshUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Dashboard refresh failed.');
            }

            const data = await response.json();

            
            
const totalProductsEl = document.getElementById('statTotalProducts');
const totalStockQuantityEl = document.getElementById('statTotalStockQuantity');
const inventoryValueEl = document.getElementById('statInventoryValue');
const lowStockEl = document.getElementById('statLowStock');

if (totalProductsEl) {
    totalProductsEl.textContent = data.totalProducts;
}

if (totalStockQuantityEl) {
    totalStockQuantityEl.textContent =
        Number(data.totalStockQuantity).toLocaleString('en-PH');
}

if (inventoryValueEl) {
    inventoryValueEl.textContent = '₱' +
        Number(data.inventoryValue).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
}

if (lowStockEl) {
    lowStockEl.textContent = data.lowStock;
}

      
            const stockCanvas = document.getElementById('stockChart');

            if (stockCanvas && typeof Chart !== 'undefined') {
                const chart = Chart.getChart(stockCanvas);

                if (chart) {
                    chart.data.datasets[0].data = [
                        Number(data.inStockCount),
                        Number(data.lowStock),
                        Number(data.outOfStock)
                    ];

                    chart.update('none');
                }
            }

        } catch (error) {
            console.error(
                'Automatic dashboard refresh error:',
                error
            );
        }
    }

    
    refreshDashboard();
    setInterval(refreshDashboard, 30000);
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');

    if (!bell || !dropdown) return;

    bell.addEventListener('click', function (event) {
        event.stopPropagation();

        const isOpen = dropdown.classList.toggle('show');
        bell.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target) && !bell.contains(event.target)) {
            dropdown.classList.remove('show');
            bell.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
</x-app-layout>