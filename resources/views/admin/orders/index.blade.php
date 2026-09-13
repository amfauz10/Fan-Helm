@extends('admin.layouts.admin')

@section('title', 'Daftar Pesanan - Fan Helmet')
@section('page_title', 'Kelola Pesanan Pelanggan')

@section('content')
    <style>
        .segmented-filter-bar {
            display: flex;
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 6px;
            border-radius: var(--radius);
            gap: 6px;
            margin-bottom: 24px;
            overflow-x: auto;
        }
        .filter-tab {
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .filter-tab:hover {
            background: var(--bg-main);
            color: var(--text-title);
        }
        .filter-tab.active {
            background: var(--text-title);
            color: #ffffff;
        }
    </style>

    <!-- SEGMENTED FILTER BAR -->
    <div class="segmented-filter-bar">
        <a href="{{ route('admin.orders.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
            ⏳ Menunggu
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'success']) }}" class="filter-tab {{ request('status') == 'success' ? 'active' : '' }}">
            ✅ Lunas / Sukses
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="filter-tab {{ request('status') == 'shipped' ? 'active' : '' }}">
            🚚 Pengiriman
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            🎉 Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            ❌ Dibatalkan
        </a>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Daftar Transaksi Pesanan ({{ $orders->total() }})</h3>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">Kelola pengiriman dan perbarui status transaksi</p>
            </div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Alamat Pengiriman</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Waktu Masuk</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $ord)
                        <tr>
                            <td>
                                <span style="font-variant-numeric: tabular-nums; font-weight: 600; color: var(--text-title); font-size: 0.9rem;">
                                    {{ $ord->order_code }}
                                </span>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $ord->items->count() }} item helm</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--bg-main); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem; color: var(--text-title); flex-shrink: 0;">
                                        {{ strtoupper(substr($ord->customer_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-title);">{{ $ord->customer_name }}</div>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $ord->customer_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.88rem; color: var(--text-title); font-weight: 600;">{{ $ord->customer_phone }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $ord->customer_address }}
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-title); font-size: 0.95rem;">{{ $ord->formatted_total }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $ord->status_badge_class }}">
                                    {{ $ord->status_label }}
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">
                                {{ $ord->created_at->format('d M Y') }}<br>
                                <span style="font-size: 0.75rem;">{{ $ord->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-secondary btn-sm">
                                    <i class='bx bx-search-alt'></i> Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                                <i class='bx bx-package' style="font-size: 2.5rem; display: block; margin-bottom: 8px;"></i>
                                Tidak ada transaksi pesanan yang sesuai dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 18px;">
        {{ $orders->links() }}
    </div>
@endsection
