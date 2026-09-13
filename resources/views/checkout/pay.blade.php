<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <title>Selesaikan Pembayaran - Fan Helm</title>

    {{-- Sandbox saat testing, otomatis ganti ke domain production sesuai config --}}
    <script
        src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}"></script>

    <style>
        .pay-wrap { max-width: 480px; margin: 60px auto; text-align: center; padding: 0 20px; font-family: 'Plus Jakarta Sans', sans-serif; }
        .pay-box { background: var(--fog); border: 1px dashed var(--mist); border-radius: var(--radius-sm); padding: 20px; margin: 20px 0; text-align: left; }
        .pay-box p { margin: 4px 0; color: var(--slate); }
        .btn-pay { background: var(--first-color); color: #fff; border: none; padding: 14px 28px; border-radius: var(--radius-sm); font-size: 1rem; cursor: pointer; width: 100%; }
        .btn-pay:disabled { opacity: .6; cursor: not-allowed; }
        #pay-status { margin-top: 16px; font-size: .9rem; color: var(--slate); }
        .pay-methods { font-size: .8rem; color: var(--slate); margin-bottom: 16px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="pay-wrap">
        <h2>Selesaikan Pembayaran</h2>
        <div class="pay-box">
            <p>Nomor Pesanan: <strong>{{ $order->order_code }}</strong></p>
            <p>Total Tagihan: <strong>{{ $order->formatted_total }}</strong></p>
            <p>Atas Nama: <strong>{{ $order->customer_name }}</strong></p>
        </div>

        <p class="pay-methods">
            Tersedia: Kartu Kredit, GoPay, ShopeePay, QRIS (DANA/OVO/LinkAja),
            Transfer Bank/VA (BCA, BNI, BRI, Permata, dll), Mandiri m-Banking,
            Indomaret/Alfamart, Akulaku &amp; Kredivo
        </p>

        <button id="pay-button" class="btn-pay">Bayar Sekarang</button>
        <div id="pay-status"></div>
    </div>

    <script>
        const snapToken = @json($snapToken);
        const orderCode = @json($order->order_code);
        const statusUrl = @json(route('checkout.status', $order->order_code));
        const successUrl = @json(route('checkout.success', $order->order_code));
        const cartUrl = @json(route('cart.index'));
        const statusEl = document.getElementById('pay-status');
        const payButton = document.getElementById('pay-button');

        function openSnap() {
            payButton.disabled = true;
            window.snap.pay(snapToken, {
                onSuccess: function () { window.location.href = successUrl; },
                onPending: function () {
                    statusEl.textContent = 'Menunggu konfirmasi pembayaran... halaman akan otomatis diperbarui.';
                    payButton.disabled = false;
                    startPolling();
                },
                onError: function () {
                    statusEl.textContent = 'Pembayaran gagal diproses. Silakan coba lagi.';
                    payButton.disabled = false;
                },
                onClose: function () {
                    statusEl.textContent = 'Anda menutup jendela pembayaran sebelum selesai. Klik tombol di atas untuk mencoba lagi.';
                    payButton.disabled = false;
                }
            });
        }

        payButton.addEventListener('click', openSnap);

        // Polling ringan: begitu webhook Midtrans mengonfirmasi status di backend,
        // pelanggan otomatis diarahkan tanpa perlu klik apa pun.
        let pollTimer = null;
        function startPolling() {
            if (pollTimer) return;
            pollTimer = setInterval(function () {
                fetch(statusUrl)
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.redirect_url) {
                            clearInterval(pollTimer);
                            window.location.href = data.redirect_url;
                        }
                    });
            }, 4000);
        }

        // Buka popup otomatis begitu halaman dimuat
        openSnap();
    </script>
</body>
</html>