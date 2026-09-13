<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_code }} - Fan Helm</title>

    <style type="text/css">
        .invoice-box {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            font-size: 15px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            color: #333;
            background: #fff;
            border-radius: var(--radius-sm);
        }
        
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        
        .invoice-box table td {
            padding: 10px;
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }   

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 30px;
        }
        
        .invoice-box table tr.heading td {
            background: #f4f4f5;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #111;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 1.1rem;
            color: #1B1E21;
        }

        .action-buttons {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-action {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
        }
        .btn-print {
            background-color: #1B1E21;
            color: #fff;
        }
        .btn-back {
            background-color: var(--mist-soft);
            color: var(--ink);
            border: 1px solid var(--mist);
        }

        @media print {
            .action-buttons {
                display: none;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
            .invoice-box table tr td:nth-child(2) {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn-action btn-back">&larr; Kembali ke Beranda</a>
        <button onclick="window.print()" class="btn-action btn-print">Cetak Invoice</button>
    </div>

    <!-- Starting Div -->
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <h2 style="margin: 0; color: #1B1E21;">Fan Helm</h2>
                                <span style="font-size: 0.85rem; color: #666;">Official Helmet Store</span>
                            </td>
                            <td>
                                <strong>INVOICE</strong><br>
                                ID: {{ $order->order_code }}<br>
                                Tanggal: {{ $order->created_at->format('d M Y, H:i') }} WIB<br>
                                Status: <span style="color: #10b981; font-weight: bold;">Lunas / Sukses</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Penjual:</strong><br>
                                Fan Helm Official Store<br>
                                Jl. Eyang Weri, Kuningan 45511 - Indonesia<br>
                                Phone: +62-821-1807-9547
                            </td>

                            <td>
                                <strong>Tujuan Pengiriman:</strong><br>
                                <strong>{{ $order->customer_name }}</strong><br>
                                Email: {{ $order->customer_email }}<br>
                                Telepon: {{ $order->customer_phone }}<br>
                                Alamat: {{ $order->customer_address }}
                                @if($order->shipping_city)
                                    <br>Kota: {{ $order->shipping_city }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>  
            </tr>

            <tr class="heading">
                <td>Metode Pembayaran</td>
                <td>Total Tagihan</td>
            </tr>

            <tr class="details">
                <td>
                    {{ $order->payment_type ? strtoupper(str_replace('_', ' ', $order->payment_type)) : 'Midtrans Payment Gateway' }}<br>
                    <span style="font-size: 0.85rem; color: #666;">
                        Status: {{ $order->status_label }}
                        @if($order->paid_at) &middot; Dibayar: {{ $order->paid_at->format('d M Y H:i') }} @endif
                    </span>
                </td>
                <td style="font-weight: bold;">{{ $order->formatted_total }}</td>
            </tr>

            <tr class="heading">
                <td>Rincian Produk (Item)</td>
                <td>Subtotal</td>
            </tr>

            @foreach($order->items as $item)
                <tr class="item">
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <span style="font-size: 0.85rem; color: #666;">
                            Ukuran: {{ $item->size }} &bull; Jumlah: {{ $item->quantity }} x Rp. {{ number_format($item->price, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>{{ $item->formatted_subtotal }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td>
                    Subtotal Produk: {{ $order->formatted_subtotal }}<br>
                    Ongkos Kirim: {{ $order->formatted_shipping_cost }}<br>
                    <strong>Total: {{ $order->formatted_total }}</strong>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 40px; border-top: 1px dashed #ddd; padding-top: 15px; font-size: 0.8rem; color: #777; text-align: center;">
            Terima kasih telah berbelanja di <strong>Fan Helm</strong>. Harap simpan invoice ini sebagai bukti sah pembelian Anda.
        </div>
    </div>
</body>         
</html>