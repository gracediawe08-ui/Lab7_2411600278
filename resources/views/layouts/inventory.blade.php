<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hardware Inventory System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --mauve: #8E5A83;
            --mauve-dark: #75476B;
            --pink: #E5989B;
            --light-pink: #FFF7F8;
            --text: #4A3B47;
            --muted: #9A8B95;
            --border: #F1E5EA;
            --topbar-height: 64px;
            --sidebar-width: 245px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', Arial, sans-serif;
            color: var(--text);
            background: var(--light-pink);
        }

        .inventory-topbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            box-shadow: 0 2px 8px rgba(74, 59, 71, .04);
        }

        .topbar-brand {
            font-size: 17px;
            font-weight: 800;
            color: var(--mauve);
            letter-spacing: .2px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: var(--text);
        }

        .topbar-avatar {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #FCE4E8;
            color: var(--mauve);
            font-weight: 800;
        }

        .inventory-sidebar {
            position: fixed;
            top: var(--topbar-height);
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            background: var(--mauve);
            color: #fff;
            padding: 25px 17px;
            display: flex;
            flex-direction: column;
            z-index: 90;
            overflow-y: auto;
        }

        .brand {
            padding: 0 12px 22px;
        }

        .brand-title {
            font-size: 21px;
            line-height: 1.25;
            font-weight: 800;
            color: #fff;
        }

        .brand-subtitle {
            margin-top: 7px;
            font-size: 11px;
            color: rgba(255,255,255,.72);
            letter-spacing: .5px;
        }

        .sidebar-line {
            height: 1px;
            background: rgba(255,255,255,.2);
            margin-bottom: 22px;
        }

        .sidebar-label {
            padding: 0 12px;
            margin-bottom: 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.65);
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px;
            border-radius: 10px;
            color: rgba(255,255,255,.85);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255,255,255,.17);
            color: #fff;
        }

        .sidebar-icon {
            width: 21px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-button {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 10px;
            background: transparent;
            color: #fff;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .logout-button:hover {
            background: rgba(255,255,255,.15);
        }

        .inventory-main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            background: var(--light-pink);
        }

        .main-container {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 30px;
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 190px;
            }

            .inventory-sidebar {
                padding: 20px 10px;
            }

            .brand-title {
                font-size: 17px;
            }

            .main-container {
                padding: 20px 14px;
            }

            .inventory-topbar {
                padding: 0 15px;
            }
        }

        @media (max-width: 560px) {
            :root {
                --sidebar-width: 0px;
            }

            .inventory-sidebar {
                display: none;
            }

            .inventory-main {
                margin-left: 0;
            }

            .topbar-brand {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation -->
    <header class="inventory-topbar">
        <div class="topbar-brand">Hardware Inventory System</div>

        <div class="topbar-right">
            <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
            <div class="topbar-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="inventory-sidebar">
        <div class="brand">
            <div class="brand-title">
                Hardware<br>Inventory System
            </div>
            <div class="brand-subtitle">Construction Materials</div>
        </div>

        <div class="sidebar-line"></div>

        <div class="sidebar-label">MAIN MENU</div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon">⌂</span>
                Dashboard
            </a>

            <a href="{{ route('products.index') }}"
               class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="sidebar-icon">▣</span>
                Products
            </a>

            <a href="{{ route('products.create') }}"
               class="sidebar-link">
                <span class="sidebar-icon">＋</span>
                Add Product
            </a>

            <a href="{{ route('inventory.index') }}"
               class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <span class="sidebar-icon">▤</span>
                Inventory
            </a>

            <a href="#analytics" class="sidebar-link">
                <span class="sidebar-icon">◔</span>
                Analytics
            </a>

            <a href="#reports" class="sidebar-link">
                <span class="sidebar-icon">▥</span>
                Reports
            </a>

            <a href="{{ route('suppliers.index') }}"
               class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🏢</span>
                Suppliers
            </a>
        </nav>

        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    ↪ Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Page Content -->
    <main class="inventory-main">
        <div class="main-container">
            @yield('content')
        </div>
    </main>
</body>
</html>