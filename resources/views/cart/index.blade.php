@extends('layouts.app')

@section('title', 'Keranjang Belanja - Fan Helm')

@section('content')
<div class="bd-container" style="padding-top: calc(var(--header-height) + 1.5rem); padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div style="margin-bottom: 24px; font-size: 0.88rem; color: var(--slate);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;"><i class='bx bxs-home'></i> Beranda</a>
        <span style="margin: 0 8px;">&bull;</span>
        <span style="color: var(--ink); font-weight: 600;">Keranjang Belanja</span>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--mist); box-shadow: none; max-width: 600px; margin: 40px auto;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--fog); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: var(--slate-soft); font-size: 40px;">
                <i class='bx bx-shopping-bag'></i>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--ink); margin-bottom: 8px;">Keranjang Belanja Kosong</h2>
            <p style="color: var(--slate); font-size: 0.95rem; margin-bottom: 28px; line-height: 1.5;">
                Anda belum memilih helm. Jelajahi katalog helm pilihan kami dan temukan helm impian Anda.
            </p>
            <a href="{{ route('home') }}#products" class="button" style="padding: 12px 28px; font-size: 0.95rem;">
                Lihat Koleksi Helm &rarr;
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1.7fr 1fr; gap: 32px; align-items: start;">
            <!-- LEFT: CART ITEMS & CHECKOUT FORM -->
            <div>
                <!-- CART ITEMS TABLE -->
                <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--mist); padding: 24px; box-shadow: none; margin-bottom: 28px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--mist-soft);">
                        <h3 style="font-size: 1.15rem; font-weight: 600; color: var(--ink);">Item di Keranjang ({{ count($cart) }})</h3>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua helm di keranjang?')">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                <i class='bx bx-trash'></i> Kosongkan
                            </button>
                        </form>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($cart as $key => $item)
                            <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--fog);">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: contain; border-radius: var(--radius-sm); background: var(--fog); border: 1px solid var(--mist); padding: 4px;">
                                
                                <div style="flex: 1;">
                                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--ink); margin-bottom: 4px;">
                                        {{ $item['name'] }}
                                    </h4>
                                    <div style="font-size: 0.82rem; color: var(--slate); display: flex; gap: 10px; align-items: center;">
                                        <span class="badge badge-secondary">Ukuran: {{ $item['size'] }}</span>
                                        <span>Jumlah: {{ $item['quantity'] }} pcs</span>
                                    </div>
                                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--first-color); margin-top: 6px;">
                                        Rp. {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        <span style="font-size: 0.75rem; color: var(--slate-soft); font-weight: normal;">(@ Rp. {{ number_format($item['price'], 0, ',', '.') }})</span>
                                    </div>
                                </div>

                                <div>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid #fecaca; background: #fff5f5; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;" title="Hapus Helm">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- CHECKOUT BUYER FORM -->
                <div id="checkout-form-section" style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--mist); padding: 28px; box-shadow: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 1.2rem; font-weight: 600; color: var(--ink);">
                            <i class='bx bx-user-pin' style="color: var(--first-color);"></i> Informasi Pengiriman
                        </h3>

                        @auth
                            <span class="badge badge-success" style="font-size: 0.8rem;">
                                <i class='bx bx-check-double'></i> Login: {{ auth()->user()->name }}
                            </span>
                        @else
                            <span style="font-size: 0.82rem; color: var(--slate);">
                                Punya akun? <a href="{{ route('login') }}" style="color: var(--first-color); font-weight: 600;">Masuk di sini</a>
                            </span>
                        @endauth
                    </div>

                    @if ($errors->any())
                        <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 16px; margin-bottom: 20px; border-radius: 6px;">
                            <ul style="margin: 0; padding-left: 18px; color: #991b1b; font-size: 0.88rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        @php
                            $user = auth()->user();
                            $defaultName = old('namaLengkap', $user->name ?? '');
                            $defaultEmail = old('emailAddress', $user->email ?? '');
                            $defaultPhone = old('noHP', $user->phone ?? '');
                            $defaultAddress = old('alamatLengkap', $user->address ?? '');
                        @endphp

                        <div style="margin-bottom: 18px;">
                            <label style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--ink); margin-bottom: 6px;">
                                Nama Lengkap Penerima *
                            </label>
                            <input type="text" name="namaLengkap" value="{{ $defaultName }}" class="form-control" placeholder="Nama Lengkap Anda" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--mist); border-radius: var(--radius-sm); font-size: 0.92rem;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--ink); margin-bottom: 6px;">
                                    Alamat Email *
                                </label>
                                <input type="email" name="emailAddress" value="{{ $defaultEmail }}" class="form-control" placeholder="nama@email.com" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--mist); border-radius: var(--radius-sm); font-size: 0.92rem;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--ink); margin-bottom: 6px;">
                                    No. Telepon / WhatsApp *
                                </label>
                                <input type="text" name="noHP" value="{{ $defaultPhone }}" class="form-control" placeholder="08xxxxxxxxxx" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--mist); border-radius: var(--radius-sm); font-size: 0.92rem;">
                            </div>
                        </div>

                        <div style="margin-bottom: 18px;">
                            <label style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--ink); margin-bottom: 6px;">
                                Alamat Lengkap Pengiriman *
                            </label>
                            <textarea name="alamatLengkap" rows="3" class="form-control" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan, Kode Pos" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--mist); border-radius: var(--radius-sm); font-size: 0.92rem;">{{ $defaultAddress }}</textarea>
                        </div>

                        <div style="margin-bottom: 18px;">
                            <label style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--ink); margin-bottom: 6px;">
                                Kota Tujuan Pengiriman *
                            </label>
                            <select name="kotaTujuan" id="kotaTujuan" class="form-control" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--mist); border-radius: var(--radius-sm); font-size: 0.92rem;">
                                <option value="" disabled {{ old('kotaTujuan') ? '' : 'selected' }}>-- Pilih Kota --</option>
                                @foreach($shippingCities as $city => $cost)
                                    <option value="{{ $city }}" data-cost="{{ $cost }}" {{ old('kotaTujuan') === $city ? 'selected' : '' }}>
                                        {{ $city }} (Rp. {{ number_format($cost, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <div style="font-size: 0.78rem; color: var(--slate-soft); margin-top: 4px;">
                                Ongkir dihitung otomatis berdasarkan kota tujuan. Kalau kotamu tidak ada di daftar, pilih "Lainnya".
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT: SUMMARY CARD -->
            <div>
                <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--mist); padding: 28px; box-shadow: none; position: sticky; top: 100px;">
                    <h3 style="font-size: 1.2rem; font-weight: 600; color: var(--ink); margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--mist-soft);">
                        Ringkasan Belanja
                    </h3>

                    <div style="font-size: 0.9rem; line-height: 2; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--slate);">ID Pesanan Baru:</span>
                            <strong>{{ $suggestedOrderCode }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--slate);">Metode Pembayaran:</span>
                            <strong style="color: #0284c7;">E-wallet / VA / QRIS / Kartu</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--slate);">Subtotal Produk:</span>
                            <strong id="summarySubtotal">Rp. {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--slate);">Ongkos Kirim:</span>
                            <strong id="summaryShipping">Pilih kota dulu</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px dashed var(--mist);">
                            <span style="font-size: 1.05rem; font-weight: 600; color: var(--ink);">Total Tagihan:</span>
                            <span style="font-size: 1.3rem; font-weight: 600; color: var(--first-color);" id="summaryTotal">
                                Rp. {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="button" onclick="submitCheckout()" class="button" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem; border-radius: var(--radius-sm); border: none; cursor: pointer; box-shadow: none;">
                        <i class='bx bx-check-shield' style="font-size: 1.25rem; margin-right: 6px;"></i> Bayar Sekarang
                    </button>

                    <div style="margin-top: 16px; text-align: center; font-size: 0.78rem; color: var(--slate-soft); line-height: 1.5;">
                        <i class='bx bx-lock-alt'></i> Transaksi 100% aman & terlindungi oleh Fan Helmet.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function submitCheckout() {
        var form = document.getElementById('checkoutForm');
        if (form) {
            if (form.reportValidity()) {
                form.submit();
            }
        }
    }

    (function () {
        var subtotal = {{ (int) $total }};
        var citySelect = document.getElementById('kotaTujuan');
        var shippingEl = document.getElementById('summaryShipping');
        var totalEl = document.getElementById('summaryTotal');

        function formatRupiah(number) {
            return 'Rp. ' + Math.round(number).toLocaleString('id-ID');
        }

        function updateSummary() {
            if (!citySelect || citySelect.selectedIndex < 0) return;
            var selected = citySelect.options[citySelect.selectedIndex];
            var cost = parseInt(selected.getAttribute('data-cost') || '0', 10);

            if (!selected.value) {
                shippingEl.textContent = 'Pilih kota dulu';
                totalEl.textContent = formatRupiah(subtotal);
                return;
            }

            // Nilai ini HANYA untuk preview - perhitungan final & anti-manipulasi
            // tetap dilakukan ulang di server (CheckoutController::process()).
            shippingEl.textContent = formatRupiah(cost);
            totalEl.textContent = formatRupiah(subtotal + cost);
        }

        if (citySelect) {
            citySelect.addEventListener('change', updateSummary);
            updateSummary();
        }
    })();
</script>
@endpush
