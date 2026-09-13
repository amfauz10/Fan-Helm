<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--========== BOX ICONS ==========-->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--========== BASE CSS ==========-->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    
    <title>@yield('title', 'Fan Helm - Toko Helm Premium Resmi')</title>

    <style>
        /* Bekas garis balap — dinonaktifkan lewat design-system.css */
        .race-stripe {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-dark) 0%, var(--first-color) 45%, var(--accent-yellow) 100%);
            z-index: 1001;
        }

        /* HEADER & NAVBAR MODERNIZATION */
        .l-header {
            position: fixed;
            top: 4px;
            left: 0;
            width: 100%;
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .nav {
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav__logo {
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--title-color);
            letter-spacing: -0.3px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav__logo i {
            color: var(--first-color);
            font-size: 1.6rem;
        }

        .nav__link {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--text-color);
            transition: color 0.2s ease;
        }
        .nav__link:hover, .active-link {
            color: var(--first-color) !important;
        }

        .nav__right-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* USER MENU DROPDOWN */
        .user-dropdown {
            position: relative;
        }
        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 6px 14px 6px 8px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .user-btn:hover {
            background: #e2e8f0;
        }
        .user-avatar-sm {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--first-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .user-menu-box {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            width: 220px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            border: 1px solid #e2e8f0;
            padding: 8px 0;
            z-index: 1000;
            animation: fadeInMenu 0.2s ease;
        }
        .user-dropdown:hover .user-menu-box,
        .user-menu-box:hover {
            display: block;
        }
        @keyframes fadeInMenu {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .user-menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            transition: background 0.15s;
        }
        .user-menu-item:hover {
            background: #f8fafc;
            color: var(--first-color);
        }
        .user-menu-item i {
            font-size: 1.15rem;
            color: #64748b;
        }
        .user-menu-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 6px 0;
        }

        .btn-auth-link {
            font-weight: 600;
            font-size: 0.88rem;
            padding: 7px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-auth-login {
            color: #334155;
            background: #f1f5f9;
        }
        .btn-auth-login:hover {
            background: #e2e8f0;
        }
        .btn-auth-register {
            background: var(--first-color);
            color: #ffffff !important;
            box-shadow: none;
        }
        .btn-auth-register:hover {
            background: var(--first-color-alt);
        }

        /* CART ICON */
        .nav__icon {
            position: relative;
            font-size: 1.45rem;
            color: var(--title-color);
            display: flex;
            align-items: center;
        }
        .nav__icon span {
            position: absolute;
            top: -6px;
            right: -10px;
            background-color: var(--first-color);
            color: #fff;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
        }

        /* FLASH NOTIFICATIONS */
        .flash-alert {
            position: fixed;
            top: 90px;
            right: 24px;
            z-index: 2000;
            padding: 14px 22px;
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            animation: fadeInSlide 0.3s ease;
        }
        .flash-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .flash-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        @keyframes fadeInSlide {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-primary { background: #e0e7ff; color: #4338ca; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        /* ENERGETIC PRIMARY BUTTON */
        .btn-energy {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--first-color) 0%, var(--first-color-alt) 100%);
            color: #fff !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.4px;
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-energy:hover {
            transform: translateY(-2px);
            box-shadow: none;
        }

        /* FOOTER MODERN */
        .footer {
            background: #0f172a;
            color: #cbd5e1;
            padding: 60px 0 25px;
            margin-top: 60px;
        }
        .footer__logo {
            color: #fff !important;
            font-weight: 800;
            font-size: 1.4rem;
        }
        .footer__title {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .footer__link {
            color: #94a3b8 !important;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .footer__link:hover {
            color: var(--first-color) !important;
        }
        .footer__copy {
            text-align: center;
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="race-stripe"></div>

    <!--========== SCROLL TOP ==========-->
    <a href="#" class="scrolltop" id="scroll-top">
        <i class='bx bx-chevron-up scrolltop__icon'></i>
    </a>

    <!--========== FLASH NOTIFICATIONS ==========-->
    @if(session('success'))
        <div class="flash-alert flash-success" id="flash-message">
            <i class='bx bx-check-circle' style="font-size: 1.4rem; color: #059669;"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-message').style.display='none'" style="background:none;border:none;cursor:pointer;margin-left:8px;font-weight:bold;color:inherit;font-size:1.2rem;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="flash-alert flash-error" id="flash-message">
            <i class='bx bx-error-circle' style="font-size: 1.4rem; color: #dc2626;"></i>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-message').style.display='none'" style="background:none;border:none;cursor:pointer;margin-left:8px;font-weight:bold;color:inherit;font-size:1.2rem;">&times;</button>
        </div>
    @endif

    <!--========== HEADER ==========-->
    <header class="l-header" id="header">
        <nav class="nav bd-container">
            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>

            <a href="{{ route('home') }}" class="nav__logo">
                <i class='bx bxs-shield'></i> Fan Helm
            </a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="{{ route('home') }}#home" class="nav__link {{ request()->routeIs('home') ? 'active-link' : '' }}">Beranda</a></li>
                    <li class="nav__item"><a href="{{ route('home') }}#products" class="nav__link">Katalog Produk</a></li>
                    <li class="nav__item"><a href="{{ route('home') }}#about" class="nav__link">Tentang Kami</a></li>
                    <li class="nav__item"><a href="{{ route('home') }}#size" class="nav__link">Panduan Ukuran</a></li>
                    <li class="nav__item"><a href="{{ route('contact.index') }}" class="nav__link {{ request()->routeIs('contact.index') ? 'active-link' : '' }}">Hubungi Kami</a></li>
                </ul>
            </div>              
   
            <div class="nav__right-actions">
                <!-- CART COMPONENT -->
                @php
                    $headerCart = session('cart', []);
                    $headerCartCount = 0;
                    $headerCartTotal = 0;
                    foreach($headerCart as $item) {
                        $headerCartCount += $item['quantity'];
                        $headerCartTotal += $item['price'] * $item['quantity'];
                    }
                @endphp

                <div class="nav__shop">
                    <a href="{{ route('cart.index') }}" class="nav__icon" title="Keranjang Belanja">
                        <i class='bx bx-shopping-bag'></i>
                        <span>{{ $headerCartCount }}</span>
                    </a>
                    
                    <div class="nav__shop-content">
                        <div class="title_shop">
                            <span>Keranjang ({{ $headerCartCount }} Helm)</span>
                        </div>
                    
                        @forelse($headerCart as $key => $item)
                            <div class="item">                       
                                <div class="image_cart">
                                    <div class="image_c">
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                    </div>
                                </div>                     
                                <div class="description">
                                    <span>{{ $item['name'] }}</span>
                                    <span>{{ $item['quantity'] }} pcs ({{ $item['size'] }})</span>
                                </div>                            
                                <div class="total-price">Rp. {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                                <form action="{{ route('cart.remove', $key) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="delete-item" style="background:none;border:none;cursor:pointer;" title="Hapus">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div style="padding: 24px 16px; text-align: center; color: #94a3b8; font-size: 0.88rem;">
                                <i class='bx bx-cart' style="font-size: 2.2rem; display: block; margin-bottom: 6px; color: #cbd5e1;"></i>
                                Keranjang belanja masih kosong.
                            </div>
                        @endforelse

                        @if(count($headerCart) > 0)
                            <div class="total-all">
                                <span>Total</span>
                                <span>Rp. {{ number_format($headerCartTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="btn-viewcard">
                                <a href="{{ route('cart.index') }}" class="button-top">Lihat Keranjang</a>
                            </div>
                            <div class="btn-checkout">
                                <a href="{{ route('cart.index') }}#checkout-form-section" class="button-top2">Check Out</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- USER AUTH BUTTONS / DROPDOWN -->
                @auth
                    <div class="user-dropdown">
                        <div class="user-btn">
                            <div class="user-avatar-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <i class='bx bx-chevron-down' style="font-size: 1.1rem; color: #64748b;"></i>
                        </div>
                        <div class="user-menu-box">
                            <div style="padding: 8px 18px 10px; border-bottom: 1px solid #f1f5f9;">
                                <div style="font-size: 0.85rem; font-weight: 700; color: #0f172a;">{{ auth()->user()->name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ auth()->user()->email }}</div>
                            </div>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="user-menu-item" style="color: #b45309; background: #fffbeb;">
                                    <i class='bx bxs-dashboard' style="color: #f59e0b;"></i>
                                    <span>Panel Admin</span>
                                </a>
                                <div class="user-menu-divider"></div>
                            @endif

                            <a href="{{ route('customer.orders') }}" class="user-menu-item">
                                <i class='bx bx-receipt'></i>
                                <span>Pesanan Saya</span>
                            </a>
                            <a href="{{ route('cart.index') }}" class="user-menu-item">
                                <i class='bx bx-cart'></i>
                                <span>Keranjang</span>
                            </a>

                            <div class="user-menu-divider"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="user-menu-item" style="width: 100%; border: none; background: none; cursor: pointer; color: #ef4444;">
                                    <i class='bx bx-log-out' style="color: #ef4444;"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a href="{{ route('login') }}" class="btn-auth-link btn-auth-login">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-auth-link btn-auth-register">Daftar</a>
                    </div>
                @endauth
            </div>                 
        </nav>
    </header>

    <main class="l-main">
        @yield('content')
    </main>

    <!--========== FOOTER ==========-->
    <footer class="footer section">
        <div class="footer__container bd-container bd-grid">
            <div class="footer__content">
                <a href="{{ route('home') }}" class="footer__logo">
                    <i class='bx bxs-shield' style="color: var(--first-color);"></i> Fan Helmet
                </a>
                <p style="font-size: 0.88rem; color: #94a3b8; margin: 12px 0 16px; line-height: 1.6;">
                    Pusat helm berkualitas tinggi berstandar SNI resmi. Melindungi perjalanan Anda dengan kenyamanan maksimal.
                </p>
                <div>
                    <a href="#" class="footer__social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-instagram'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">Layanan Pelanggan</h3>
                <ul>
                    <li><a href="#" class="footer__link">Pengiriman & Pengembalian</a></li>
                    <li><a href="{{ route('home') }}#size" class="footer__link">Panduan Pengukuran</a></li>
                    <li><a href="{{ route('home') }}#size" class="footer__link">Tabel Konversi Ukuran</a></li>
                    <li><a href="{{ route('customer.orders') }}" class="footer__link">Lacak Status Pesanan</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">Informasi & Bantuan</h3>
                <ul>
                    <li><a href="{{ route('home') }}#products" class="footer__link">Koleksi Helm Mingguan</a></li>
                    <li><a href="{{ route('contact.index') }}" class="footer__link">Hubungi Kami (24/7)</a></li>
                    <li><a href="#" class="footer__link">Kebijakan Privasi</a></li>
                    <li><a href="#" class="footer__link">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">Lokasi & Kontak</h3>
                <ul style="font-size: 0.88rem; color: #94a3b8; line-height: 1.8;">
                    <li><i class='bx bx-map-pin' style="color: var(--first-color);"></i> Jl. Eyang Weri, Kuningan 45511</li>
                    <li><i class='bx bx-phone' style="color: var(--first-color);"></i> +62-821-1807-9547</li>
                    <li><i class='bx bxl-whatsapp' style="color: #10b981;"></i> 0821-1807-9547 (WA Chat)</li>
                    <li><i class='bx bx-envelope' style="color: var(--first-color);"></i> support@fanhelm.com</li>
                </ul>
            </div>
        </div>

        <p class="footer__copy">&#169; {{ 2023 }} Fauzi Akbar . All Right reserved.</p>
    </footer>

    <!--========== SCRIPTS ==========-->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>

    <script>
        setTimeout(() => {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.opacity = '0';
                flash.style.transition = 'opacity 0.4s ease';
                setTimeout(() => flash.remove(), 400);
            }
        }, 4000);
    </script>
    @stack('scripts')
</body>
</html>