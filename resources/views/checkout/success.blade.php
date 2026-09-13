<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    
    <title>Pesanan Berhasil - Fan Helm</title>
</head>
<body onload="initSuccessPage()" style="margin:0;">
    <!--========== PAGE LOADER ==========-->
    <div id="loader"></div>
    
    <!--========== STATUS PESANAN ==========-->
    @php
        $isPaid = $order->isPaid();
        $isFailed = in_array($order->status, ['failure', 'deny', 'cancelled', 'expired']);
        $isWaiting = !$isPaid && !$isFailed;
    @endphp
    <div style="display:none; text-align:center; padding-top: 40px;" id="myDiv" class="animate-bottom">
        @if ($isPaid)
            <img src="{{ asset('assets/img/success-buy.png') }}" alt="Sukses Terbayar" width="320">
            <h2 class="section-title" style="margin-top: 20px;">Sukses Terbayar!</h2>
        @elseif ($isFailed)
            <i class='bx bx-x-circle' style="font-size: 90px; color: var(--danger);"></i>
            <h2 class="section-title" style="margin-top: 20px;">Pembayaran {{ $order->status_label }}</h2>
        @else
            <i class='bx bx-time-five' style="font-size: 90px; color: var(--warning);"></i>
            <h2 class="section-title" style="margin-top: 20px;">{{ $order->status_label }}</h2>
        @endif

        <div style="margin: 15px auto; max-width: 450px; background: var(--fog); border: 1px dashed var(--mist); border-radius: var(--radius-sm); padding: 15px;">
            <p style="margin: 0; font-size: 0.95rem; color: var(--slate);">
                Nomor Pesanan: <strong style="color: var(--ink); font-size: 1.05rem;">{{ $order->order_code }}</strong><br>
                Total: <strong style="color: var(--ink);">{{ $order->formatted_total }}</strong><br>
                Atas Nama: <strong>{{ $order->customer_name }}</strong><br>
                Status: <strong>{{ $order->status_label }}</strong>
            </p>
        </div>

        @if ($isPaid)
            <p class="about__description">
                Silakan tunggu update pengiriman terbaru dari kami via email <br>
                <strong>{{ $order->customer_email }}</strong> atau nomor WA yang sudah Anda daftarkan.
            </p>
        @elseif ($isWaiting)
            <p class="about__description">
                Pembayaran Anda sedang diverifikasi oleh sistem. Halaman ini bisa Anda refresh beberapa saat lagi,
                atau cek riwayat pesanan di akun Anda.
            </p>
        @else
            <p class="about__description">
                Pembayaran tidak berhasil diselesaikan. Silakan hubungi kami atau coba buat pesanan baru.
            </p>
        @endif

        <div class="backhome__button" style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="button">Kembali Ke Beranda</a>
            @if ($isPaid)
                <a href="{{ route('order.invoice', $order->order_code) }}" class="button" target="_blank">
                    <i class='bx bx-receipt' style="vertical-align: middle; margin-right: 4px;"></i> Tampilkan Invoice
                </a>
            @endif
        </div>
    </div>

    <!--========== MAIN JS ==========-->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        function initSuccessPage() {
            setTimeout(function() {
                var loader = document.getElementById("loader");
                var myDiv = document.getElementById("myDiv");
                if (loader) loader.style.display = "none";
                if (myDiv) myDiv.style.display = "block";
            }, 600);
        }
    </script>
</body>
</html>