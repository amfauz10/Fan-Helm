@extends('admin.layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Fan Helmet')
@section('page_title', 'Detail Transaksi ' . $order->order_code)

@section('content')
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Daftar Pesanan
        </a>
        <a href="{{ route('order.invoice', $order->order_code) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class='bx bx-printer'></i> Cetak Invoice Publik
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- LEFT: ITEMS & ORDER DETAILS -->
        <div>
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Rincian Barang Pesanan</h3>
                    <span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Foto</th>
                                <th>Produk Helm</th>
                                <th>Ukuran</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->product_image ?? 'assets/img/helmet1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: contain; border-radius: 6px; background:var(--bg-main); border: 1px solid var(--border-color);">
                                    </td>
                                    <td>
                                        <strong>{{ $item->product_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $item->size }}</span>
                                    </td>
                                    <td>{{ $item->quantity }} pcs</td>
                                    <td>Rp. {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td style="text-align: right; font-weight: 600; color: var(--text-title);">
                                        {{ $item->formatted_subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" style="text-align: right; font-weight: 600; font-size: 1.05rem;">
                                    TOTAL PEMBAYARAN:
                                </td>
                                <td style="text-align: right; font-weight: 800; font-size: 1.15rem; color: var(--primary);">
                                    {{ $order->formatted_total }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- DELIVERY ADDRESS -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Tujuan Pengiriman & Catatan</h3>
                </div>
                <div class="card-body">
                    <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-title);">
                        <strong>Alamat Lengkap:</strong><br>
                        {{ $order->customer_address }}
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT: CUSTOMER & STATUS CONTROLLER -->
        <div>
            <!-- STATUS UPDATE FORM -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Perbarui Status Pesanan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label class="form-label" for="status">Pilih Status Baru:</label>
                            <select name="status" id="status" class="form-control" style="font-weight: 600;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                                <option value="success" {{ $order->status == 'success' || $order->status == 'paid' ? 'selected' : '' }}>✅ Lunas / Sukses</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Dalam Pengiriman</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🎉 Selesai Diterima</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class='bx bx-check-circle'></i> Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- CUSTOMER INFO CARD -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pembeli</h3>
                </div>
                <div class="card-body" style="font-size: 0.9rem; line-height: 1.8;">
                    <div><strong>Nama:</strong> {{ $order->customer_name }}</div>
                    <div><strong>Email:</strong> {{ $order->customer_email }}</div>
                    <div><strong>No. Telp / WA:</strong> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" style="color: #10b981; font-weight: 600;">{{ $order->customer_phone }}</a></div>
                    @if($order->user)
                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--border-color);">
                            <span class="badge badge-info">Pelanggan Terdaftar (ID: #{{ $order->user->id }})</span>
                        </div>
                    @else
                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--border-color);">
                            <span class="badge badge-secondary">Tamu (Guest Checkout)</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PAYMENT INFO CARD -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Info Pembayaran (Midtrans)</h3>
                </div>
                <div class="card-body" style="font-size: 0.9rem; line-height: 1.8;">
                    <div><strong>Status:</strong> <span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span></div>
                    <div><strong>Metode:</strong> {{ $order->payment_type ? strtoupper(str_replace('_', ' ', $order->payment_type)) : '-' }}</div>
                    <div><strong>ID Transaksi Midtrans:</strong> {{ $order->midtrans_transaction_id ?? '-' }}</div>
                    <div><strong>Order ID Midtrans:</strong> {{ $order->midtrans_order_id ?? '-' }}</div>
                    <div><strong>Dibayar Pada:</strong> {{ $order->paid_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection