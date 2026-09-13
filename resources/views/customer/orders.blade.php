@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - Fan Helm')

@section('content')
<section class="section bd-container" style="padding-top: 100px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div style="margin-bottom: 24px; font-size: 0.9rem; color: var(--slate);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;"><i class='bx bxs-home'></i> Beranda</a>
        &bull; <span style="color: var(--ink); font-weight: 600;">Pesanan Saya</span>
    </div>

    <!-- PROFILE HEADER -->
    <div style="background: var(--container-color, #ffffff); border-radius: var(--radius-md); padding: 24px 28px; box-shadow: none; border: 1px solid var(--mist); margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--first-color); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 600; color: var(--ink); margin-bottom: 4px;">{{ $user->name }}</h2>
                <div style="color: var(--slate); font-size: 0.88rem;">
                    <span><i class='bx bx-envelope'></i> {{ $user->email }}</span>
                    @if($user->phone)
                        <span style="margin-left: 12px;"><i class='bx bx-phone'></i> {{ $user->phone }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: var(--mist-soft); border: 1px solid var(--mist); padding: 8px 16px; border-radius: var(--radius-sm); font-size: 0.88rem; font-weight: 600; color: var(--slate); cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class='bx bx-log-out'></i> Keluar Akun
                </button>
            </form>
        </div>
    </div>

    <!-- ORDERS LIST -->
    <h3 style="font-size: 1.2rem; font-weight: 600; color: var(--ink); margin-bottom: 18px;">
        Riwayat Transaksi & Pengiriman
    </h3>

    @forelse($orders as $order)
        <div style="background: var(--container-color, #ffffff); border-radius: var(--radius-md); border: 1px solid var(--mist); box-shadow: none; margin-bottom: 18px; overflow: hidden;">
            <!-- ORDER TOP -->
            <div style="padding: 16px 20px; background: var(--fog); border-bottom: 1px solid var(--mist); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-weight: 600; color: var(--first-color); font-size: 1rem;">
                        {{ $order->order_code }}
                    </span>
                    <span style="font-size: 0.82rem; color: var(--slate);">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
                <div>
                    <span class="badge {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- ORDER ITEMS -->
            <div style="padding: 20px;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--mist-soft);">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="{{ asset($item->product_image ?? 'assets/img/helmet1.png') }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: var(--radius-sm); background: var(--fog); border: 1px solid var(--mist);">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--ink); margin-bottom: 4px;">{{ $item->product_name }}</h4>
                                <div style="font-size: 0.82rem; color: var(--slate);">
                                    Ukuran: <strong>{{ $item->size }}</strong> &bull; Jumlah: {{ $item->quantity }} pcs
                                </div>
                            </div>
                        </div>
                        <div style="font-weight: 600; font-size: 0.95rem; color: var(--ink);">
                            {{ $item->formatted_subtotal }}
                        </div>
                    </div>
                @endforeach

                <!-- ORDER FOOTER -->
                <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="font-size: 0.88rem; color: var(--slate);">
                        Tujuan: <strong>{{ $order->customer_address }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div>
                            <span style="font-size: 0.85rem; color: var(--slate);">Total Tagihan:</span>
                            <strong style="font-size: 1.15rem; color: var(--first-color); margin-left: 6px;">{{ $order->formatted_total }}</strong>
                        </div>
                        <a href="{{ route('order.invoice', $order->order_code) }}" target="_blank" class="button" style="padding: 8px 16px; font-size: 0.85rem;">
                            <i class='bx bx-receipt'></i> Lihat Invoice
                        </a>
                        @if(in_array($order->status, ['pending', 'challenge']))
                            <a href="{{ route('checkout.retry', $order->order_code) }}" class="button" style="padding: 8px 16px; font-size: 0.85rem; background: var(--first-color);">
                                <i class='bx bx-credit-card'></i> Lanjutkan Pembayaran
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: var(--radius-md); border: 1px solid var(--mist);">
            <i class='bx bx-shopping-bag' style="font-size: 4rem; color: var(--mist);"></i>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-top: 12px; color: var(--ink);">Belum Ada Riwayat Pesanan</h3>
            <p style="color: var(--slate); margin: 8px 0 24px; font-size: 0.92rem;">Anda belum melakukan pemesanan helm di toko kami.</p>
            <a href="{{ route('home') }}#products" class="button">Mulai Belanja Sekarang</a>
        </div>
    @endforelse

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
</section>
@endsection