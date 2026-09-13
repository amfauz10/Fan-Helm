@extends('emails.layout')

@section('content')
    <h2 style="margin-top:0; color:#15803d;">Pembayaran Diterima ✅</h2>
    <p>Halo {{ $order->customer_name }}, pembayaran untuk pesanan
        <strong>{{ $order->order_code }}</strong> sudah kami terima. Pesanan kamu sekarang sedang diproses.</p>

    @include('emails.partials.order-items', ['order' => $order])

    <p>Kami akan mengirim email lagi begitu pesanan kamu dikirim.</p>
@endsection
