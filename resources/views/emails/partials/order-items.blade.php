<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin:16px 0;">
    <thead>
        <tr style="background:#f3f4f6; text-align:left;">
            <th style="padding:8px; font-size:13px;">Produk</th>
            <th style="padding:8px; font-size:13px;">Ukuran</th>
            <th style="padding:8px; font-size:13px;">Qty</th>
            <th style="padding:8px; font-size:13px; text-align:right;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
        <tr style="border-bottom:1px solid #e5e7eb;">
            <td style="padding:8px; font-size:13px;">{{ $item->product_name }}</td>
            <td style="padding:8px; font-size:13px;">{{ $item->size }}</td>
            <td style="padding:8px; font-size:13px;">{{ $item->quantity }}</td>
            <td style="padding:8px; font-size:13px; text-align:right;">Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="padding:8px; font-size:13px; text-align:right; color:#6b7280;">Subtotal Produk</td>
            <td style="padding:8px; font-size:13px; text-align:right; color:#6b7280;">Rp. {{ number_format($order->subtotal_amount ?? $order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding:8px; font-size:13px; text-align:right; color:#6b7280;">Ongkos Kirim ({{ $order->shipping_city ?? '-' }})</td>
            <td style="padding:8px; font-size:13px; text-align:right; color:#6b7280;">Rp. {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding:8px; font-size:14px; font-weight:bold; text-align:right;">Total</td>
            <td style="padding:8px; font-size:14px; font-weight:bold; text-align:right;">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
