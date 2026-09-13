@extends('emails.layout')

@section('content')
    <h2 style="margin-top:0; color:#1d4ed8;">Pesanan Kamu Sedang Dikirim 🚚</h2>
    <p>Halo {{ $order->customer_name }}, pesanan <strong>{{ $order->order_code }}</strong> sudah dalam
        proses pengiriman ke alamat berikut:</p>

    <p style="background:#f3f4f6; padding:12px; border-radius:6px;">{{ $order->customer_address }}</p>

    @include('emails.partials.order-items', ['order' => $order])
@endsection
