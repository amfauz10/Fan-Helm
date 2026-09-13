@extends('emails.layout')

@section('content')
    <h2 style="margin-top:0;">Halo, {{ $order->customer_name }} 👋</h2>
    <p>Terima kasih sudah berbelanja di Fan Helmet. Pesanan kamu dengan kode
        <strong>{{ $order->order_code }}</strong> sudah kami terima dan sedang menunggu pembayaran.</p>

    @include('emails.partials.order-items', ['order' => $order])

    <p>Alamat pengiriman: {{ $order->customer_address }}</p>

    <p>
        <a href="{{ route('checkout.retry', $order->order_code) }}"
           style="display:inline-block; background:#111827; color:#ffffff; text-decoration:none; padding:10px 20px; border-radius:6px; font-size:14px;">
            Lanjutkan Pembayaran
        </a>
    </p>

    <p style="font-size:13px; color:#6b7280;">Jika kamu belum menyelesaikan pembayaran, silakan selesaikan sebelum
        batas waktu transaksi berakhir agar pesanan tidak otomatis dibatalkan.</p>
@endsection
