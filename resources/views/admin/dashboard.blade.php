@extends('admin.layouts.admin')

@section('title', 'Executive Dashboard - Fan Helmet')
@section('page_title', 'Ringkasan Eksekutif Toko')

@section('content')
    @php
        $paidCount = \App\Models\Order::whereIn('status', ['paid', 'success'])->count();
        $shippedCount = \App\Models\Order::where('status', 'shipped')->count();
        $completedCount = \App\Models\Order::where('status', 'completed')->count();
        $recentMessages = \App\Models\ContactMessage::latest()->take(3)->get();
        $totalOrdersSafe = $totalOrders > 0 ? $totalOrders : 1;
    @endphp

    <style>
        /* GREETING */
        .executive-banner {
            padding: 0 0 26px;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
        }

        .banner-date-badge {
            font-family: var(--font-mono);
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        .banner-greeting h2 {
            font-family: var(--font-display);
            font-size: 1.9rem;
            font-weight: 600;
            color: var(--text-title);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        .banner-greeting p {
            color: var(--text-muted);
            font-size: 0.92rem;
            max-width: 46ch;
        }

        /* KEY METRICS - single strip, hairline dividers, no icon chrome */
        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            background: #ffffff;
            margin-bottom: 30px;
            overflow: hidden;
        }
        @media (max-width: 900px) {
            .kpi-strip { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .kpi-strip { grid-template-columns: 1fr; }
        }

        .kpi-cell {
            padding: 22px 26px;
            border-right: 1px solid var(--border-color);
        }
        .kpi-strip .kpi-cell:last-child { border-right: none; }
        @media (max-width: 900px) {
            .kpi-cell:nth-child(2n) { border-right: none; }
            .kpi-cell:nth-child(-n+2) { border-bottom: 1px solid var(--border-color); }
        }

        .kpi-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        .kpi-value {
            font-family: var(--font-mono);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-title);
            line-height: 1.1;
            margin-bottom: 8px;
            font-variant-numeric: tabular-nums;
        }
        .kpi-note {
            font-size: 0.76rem;
            color: var(--text-muted);
        }
        .kpi-note.is-alert { color: var(--gold); font-weight: 600; }

        /* 2-COLUMN LAYOUT */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 1.85fr 1fr;
            gap: 24px;
            align-items: start;
        }
        @media (max-width: 1080px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }
        }

        /* CUSTOMER AVATAR IN TABLE */
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--bg-main);
            color: var(--text-title);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.82rem;
            flex-shrink: 0;
        }

        /* ORDER STATUS - one segmented bar instead of four separate ones,
           since these four numbers are parts of the same whole. */
        .segmented-bar {
            display: flex;
            height: 10px;
            border-radius: 4px;
            overflow: hidden;
            background: #f0f0ec;
            margin-bottom: 20px;
        }
        .segmented-bar span {
            height: 100%;
            transition: width 0.4s ease;
        }
        .status-legend {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .status-legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.87rem;
        }
        .status-legend-row .legend-label {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--text-body);
            font-weight: 500;
        }
        .status-legend-row .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 2px;
            flex-shrink: 0;
        }
        .status-legend-row .legend-value {
            font-family: var(--font-mono);
            color: var(--text-muted);
            font-size: 0.83rem;
        }

        /* MINI FEED */
        .message-feed-item {
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .message-feed-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>

    <!-- 1. GREETING -->
    <div class="executive-banner">
        <div class="banner-greeting">
            <div class="banner-date-badge">{{ date('l, d F Y') }}</div>
            <h2>Selamat datang kembali, {{ auth()->user()->name }}</h2>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class='bx bx-plus'></i> Tambah Helm Baru
            </a>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">
                Ke Toko Depan
            </a>
        </div>
    </div>

    <!-- 2. KEY METRICS -->
    <div class="kpi-strip">
        <div class="kpi-cell">
            <div class="kpi-label">Total omset penjualan</div>
            <div class="kpi-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>

        <div class="kpi-cell">
            <div class="kpi-label">Total pesanan masuk</div>
            <div class="kpi-value">{{ $totalOrders }}</div>
            @if($pendingOrders > 0)
                <div class="kpi-note is-alert">{{ $pendingOrders }} perlu diproses</div>
            @endif
        </div>

        <div class="kpi-cell">
            <div class="kpi-label">Katalog helm tersedia</div>
            <div class="kpi-value">{{ $totalProducts }}</div>
        </div>

        <div class="kpi-cell">
            <div class="kpi-label">Pelanggan terdaftar</div>
            <div class="kpi-value">{{ $totalCustomers }}</div>
        </div>
    </div>

    <!-- 3. TWO-COLUMN ACTIVITY & SUMMARY LAYOUT -->
    <div class="dashboard-layout">
        <!-- LEFT: RECENT ORDERS TABLE (65%) -->
        <div class="admin-card" style="margin-bottom: 0;">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Transaksi Pesanan Terbaru</h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">6 pesanan terakhir yang masuk ke sistem</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                    Semua pesanan
                </a>
            </div>
            <div class="card-body" style="padding: 0; overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Kode Pesanan</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr>
                                <td>
                                    <div class="customer-cell">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($ord->customer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-title);">{{ $ord->customer_name }}</div>
                                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $ord->customer_phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-mono); font-weight: 500; color: var(--text-body); font-size: 0.85rem;">
                                        {{ $ord->order_code }}
                                    </span>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $ord->items->count() }} helm
                                </td>
                                <td>
                                    <strong style="font-family: var(--font-mono); font-weight: 600; color: var(--text-title);">{{ $ord->formatted_total }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $ord->status_badge_class }}">
                                        {{ $ord->status_label }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-secondary btn-sm" style="padding: 5px 10px;">
                                        <i class='bx bx-search-alt'></i> Kelola
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                                    Belum ada transaksi pesanan masuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT: OPERATIONS & MESSAGES BREAKDOWN (35%) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- TRANSACTION STATUS BREAKDOWN -->
            <div class="admin-card" style="margin-bottom: 0;">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1.05rem;">Rasio Status Pesanan</h3>
                </div>
                <div class="card-body">
                    @php
                        $statusSegments = [
                            ['label' => 'Lunas / sukses', 'count' => $paidCount, 'color' => '#2F7A4D'],
                            ['label' => 'Dalam pengiriman', 'count' => $shippedCount, 'color' => '#3D6FA8'],
                            ['label' => 'Menunggu konfirmasi', 'count' => $pendingOrders, 'color' => '#2B6660'],
                            ['label' => 'Selesai diterima', 'count' => $completedCount, 'color' => '#1B1E21'],
                        ];
                    @endphp

                    <div class="segmented-bar">
                        @foreach($statusSegments as $seg)
                            <span style="width: {{ ($seg['count'] / $totalOrdersSafe) * 100 }}%; background: {{ $seg['color'] }};"></span>
                        @endforeach
                    </div>

                    <div class="status-legend">
                        @foreach($statusSegments as $seg)
                            <div class="status-legend-row">
                                <span class="legend-label">
                                    <span class="legend-dot" style="background: {{ $seg['color'] }};"></span>
                                    {{ $seg['label'] }}
                                </span>
                                <span class="legend-value">{{ $seg['count'] }} pesanan ({{ round(($seg['count'] / $totalOrdersSafe) * 100) }}%)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RECENT CUSTOMER MESSAGES -->
            <div class="admin-card" style="margin-bottom: 0;">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1.05rem;">Pesan Masuk Terbaru</h3>
                    <a href="{{ route('admin.messages.index') }}" style="font-size: 0.8rem; font-weight: 600; color: var(--text-body); text-decoration: none;">
                        Semua
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentMessages as $msg)
                        <div class="message-feed-item">
                            <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.78rem;">
                                {{ strtoupper(substr($msg->first_name, 0, 1)) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                    <strong style="font-size: 0.88rem; color: var(--text-title); font-weight: 600;">{{ $msg->first_name }} {{ $msg->last_name }}</strong>
                                    <span style="font-size: 0.72rem; color: var(--text-muted);">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="font-size: 0.82rem; color: var(--text-body); line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 6px;">
                                    {{ $msg->message }}
                                </p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" style="font-size: 0.75rem; font-weight: 600; color: #3f8f5f; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                    <i class='bx bxl-whatsapp'></i> Balas WhatsApp
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 24px 10px; color: var(--text-muted); font-size: 0.85rem;">
                            Belum ada pesan pelanggan baru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection