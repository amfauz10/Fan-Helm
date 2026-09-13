<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Executive - Fan Helmet')</title>

    <!-- Fonts: Space Grotesk (judul) + Inter (isi & angka) — sama dengan toko, agar terasa satu produk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Boxicons -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">

    <style>
        :root {
            /* Tanpa sidebar — navigasi jadi tab horizontal di bawah top bar,
               supaya beda dari pola dashboard admin bersidebar yang umum. */
            --ink: #1B1E21;
            --primary: #1B1E21;
            --primary-hover: #2b303a;
            --gold: #D9631E;
            --gold-soft: #FBEADC;
            --bg-main: #F1F3F1;
            --card-bg: #ffffff;
            --border-color: #E1E4E2;
            --text-title: #1B1E21;
            --text-body: #3c3f45;
            --text-muted: #767a82;
            --radius: 10px;
            --font-display: 'Space Grotesk', sans-serif;
            --font-mono: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-body);
            min-height: 100vh;
            letter-spacing: -0.1px;
        }

        /* TOP BAR — baris 1: brand + aksi. Tidak ada sidebar sama sekali;
           navigasi dipindah jadi tab horizontal di baris 2 supaya seluruh
           lebar layar dipakai untuk konten, bukan pola sidebar gelap yang
           sudah terlalu umum dipakai di mana-mana. */
        .admin-topbar-row {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 36px;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            color: var(--text-title);
        }
        .brand-icon-box {
            width: 34px;
            height: 34px;
            background: var(--gold);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #ffffff;
            flex-shrink: 0;
        }
        .brand-name {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: -0.2px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .btn-view-store {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 7px;
            background: transparent;
            color: var(--text-body);
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border-color);
            transition: border-color 0.15s, color 0.15s;
        }
        .btn-view-store:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        .admin-profile-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 16px;
            border-left: 1px solid var(--border-color);
            position: relative;
        }
        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--ink);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.88rem;
        }

        /* TAB NAV — baris 2, menggantikan sidebar */
        .admin-tabs {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 32px;
            overflow-x: auto;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .admin-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 14px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color 0.15s ease, border-color 0.15s ease;
        }
        .admin-tab i {
            font-size: 1.05rem;
        }
        .admin-tab:hover {
            color: var(--text-title);
        }
        .admin-tab.active {
            color: var(--text-title);
            border-bottom-color: var(--gold);
            font-weight: 600;
        }
        .admin-tab.active i {
            color: var(--gold);
        }
        .tab-counter {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            background: var(--gold-soft);
            color: var(--gold);
        }
        .admin-tab.active .tab-counter {
            background: var(--gold);
            color: #ffffff;
        }
        .admin-tab-exit {
            margin-left: auto;
        }

        /* MAIN WRAPPER */
        .admin-main {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* CONTENT CONTAINER */
        .admin-content {
            padding: 34px 40px 64px;
            flex: 1;
            max-width: 1360px;
        }

        /* CARD & COMPONENT SYSTEM */
        .admin-card {
            background: #ffffff;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .card-header {
            padding: 19px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-title);
            letter-spacing: -0.2px;
        }
        .card-body {
            padding: 24px;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 7px;
            font-size: 0.89rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .btn-primary {
            background: var(--ink);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        .btn-secondary {
            background: transparent;
            color: var(--text-body);
            border: 1px solid var(--border-color);
        }
        .btn-secondary:hover {
            border-color: var(--ink);
            color: var(--ink);
        }
        .btn-danger {
            background: #ffffff;
            color: #b3402f;
            border: 1px solid #e8c3bb;
        }
        .btn-danger:hover {
            background: #fbf0ee;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.81rem;
            border-radius: 6px;
        }

        /* STATUS BADGES WITH DOTS */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 2px;
            font-size: 0.81rem;
            font-weight: 600;
            color: var(--text-body);
        }
        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }
        .badge-warning::before { background: #b8862f; }
        .badge-warning { color: #8a6423; }

        .badge-success::before { background: #3f8f5f; }
        .badge-success { color: #2f6b47; }

        .badge-info::before { background: #3d6fa8; }
        .badge-info { color: #305a89; }

        .badge-primary::before { background: var(--gold); }
        .badge-primary { color: #8a6423; }

        .badge-danger::before { background: #b3402f; }
        .badge-danger { color: #9a3826; }

        .badge-secondary::before { background: #90939c; }
        .badge-secondary { color: var(--text-muted); }

        /* TABLE SYSTEM */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.89rem;
        }
        .admin-table th {
            padding: 13px 22px;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.78rem;
        }
        .admin-table td {
            padding: 16px 22px;
            border-bottom: 1px solid #f0f0ec;
            vertical-align: middle;
        }
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        .admin-table tr:hover td {
            background: #fafaf8;
        }

        /* FORMS */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 0.87rem;
            font-weight: 600;
            color: var(--text-body);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d4d4cd;
            border-radius: 7px;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.15s;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--ink);
        }

        /* ALERTS */
        .alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .alert-success { background: #eef6f0; color: #2f6b47; border: 1px solid #cbe4d2; }
        .alert-error { background: #fbf0ee; color: #9a3826; border: 1px solid #edcec8; }
    </style>
</head>
<body>

    @php
        $sidebarPendingCount = \App\Models\Order::where('status', 'pending')->count();
        $sidebarMessageCount = \App\Models\ContactMessage::count();
    @endphp

    <!-- TOP BAR: brand + aksi -->
    <div class="admin-topbar-row">
        <a href="{{ route('admin.dashboard') }}" class="brand-mark">
            <div class="brand-icon-box">
                <i class='bx bxs-shield'></i>
            </div>
            <div class="brand-name">Fan Helmet — Admin</div>
        </a>

        <div class="topbar-actions">
            <a href="{{ route('home') }}" target="_blank" class="btn-view-store">
                <i class='bx bx-globe'></i> Ke Toko Depan
            </a>

            <div class="admin-profile-pill" id="adminProfileToggle" style="cursor: pointer;">
                <div class="admin-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-title);">{{ Str::limit(auth()->user()->name ?? 'Admin', 16) }}</div>
                <i class='bx bx-chevron-down' style="font-size: 1rem; color: var(--text-muted);"></i>

                <div id="adminProfileMenu" style="display: none; position: absolute; right: 0; top: calc(100% + 10px); width: 190px; background: #fff; border: 1px solid var(--border-color); border-radius: 10px; box-shadow: 0 12px 28px rgba(27,30,33,0.12); padding: 6px; z-index: 200;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 9px 12px; border-radius: 7px; color: #b3402f; font-size: 0.87rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <i class='bx bx-log-out'></i> Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB NAV: menggantikan sidebar -->
    <nav class="admin-tabs">
        <a href="{{ route('admin.dashboard') }}" class="admin-tab {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class='bx bx-grid-alt'></i> Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}" class="admin-tab {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class='bx bx-purchase-tag-alt'></i> Katalog Helm
        </a>
        <a href="{{ route('admin.orders.index') }}" class="admin-tab {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class='bx bx-cart'></i> Pesanan Masuk
            @if($sidebarPendingCount > 0)
                <span class="tab-counter">{{ $sidebarPendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.messages.index') }}" class="admin-tab {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <i class='bx bx-message-detail'></i> Pesan Kontak
            @if($sidebarMessageCount > 0)
                <span class="tab-counter">{{ $sidebarMessageCount }}</span>
            @endif
        </a>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="admin-main">
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class='bx bx-check-circle' style="font-size: 1.3rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class='bx bx-error-circle' style="font-size: 1.3rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            var toggle = document.getElementById('adminProfileToggle');
            var menu = document.getElementById('adminProfileMenu');
            if (!toggle || !menu) return;
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });
            document.addEventListener('click', function () {
                menu.style.display = 'none';
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>