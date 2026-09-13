@extends('layouts.app')

@section('title', 'Fan Helm - Official Store Helm Premium & SNI')

@section('content')
    <style>
        .trust-banner {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1px;
            background: var(--mist);
            border: 1px solid var(--mist);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin: 20px auto 50px;
        }
        .trust-card {
            background: var(--white);
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .trust-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: var(--teal-wash);
            color: var(--teal-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .trust-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 2px;
        }
        .trust-desc {
            font-size: 0.8rem;
            color: var(--slate);
        }

        /* KARTU PRODUK */
        .product-card-modern {
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px solid var(--mist);
            padding: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 10px;
            transition: border-color 0.15s ease;
            position: relative;
        }
        .product-card-modern:hover {
            border-color: var(--teal);
        }
        .product-img-box {
            width: 100%;
            height: 190px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .product-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .product-title {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 4px;
        }
        .product-cat {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--slate);
            margin-bottom: 10px;
            display: inline-block;
        }
        .product-price {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--amber);
            margin-bottom: 16px;
        }
        .product-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }
        .btn-detail-link {
            flex: 1;
            padding: 10px;
            background: var(--ink);
            color: #fff;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-detail-link:hover {
            background: var(--teal-deep);
        }
        .btn-quick-cart {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            background: var(--fog);
            color: var(--ink);
            border: 1px solid var(--mist);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.15s;
        }
        .btn-quick-cart:hover {
            background: var(--ink);
            color: #fff;
            border-color: var(--ink);
        }
        .hero-img-wrap {
            position: relative;
            display: inline-block;
        }
    </style>

    <!--========== HOME SLIDESHOW ==========-->
    <section class="home" id="home" style="padding-top: calc(var(--header-height) + 1rem); background: var(--white); border-bottom: 1px solid var(--mist);">
        <div class="slideshow-container">
            @foreach($heroProducts as $index => $hero)
                <div class="mySlides fade">
                    <div class="home__container bd-container bd-grid" style="align-items: center;">
                        <div class="home__data">
                            <span class="section-subtitle">
                                Koleksi helm terbaik
                            </span>
                            <h1 class="home__title" style="margin: 8px 0 12px;">{{ $hero->name }}</h1>
                            <h2 class="home__subtitle" style="font-weight: 400; font-size: 1.1rem; color: var(--slate); margin-bottom: 24px;">
                                {{ $hero->subtitle }}
                            </h2>
                            <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 28px;">
                                <a href="{{ route('product.show', $hero->slug) }}" class="button" style="padding: 13px 26px; font-size: 0.95rem;">
                                    Jelajahi sekarang
                                </a>
                                <a href="#products" style="font-weight: 500; color: var(--ink); padding: 12px 18px; text-decoration: none; border-bottom: 1px solid var(--mist);">
                                    Lihat semua helm
                                </a>
                            </div>
                            <div style="display: flex; gap: 24px; flex-wrap: wrap; padding-top: 20px; border-top: 1px solid var(--mist-soft);">
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--slate); font-weight: 500;">
                                    <i class='bx bx-check-shield' style="color: var(--teal); font-size: 1.1rem;"></i> SNI Resmi
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--slate); font-weight: 500;">
                                    <i class='bx bx-sync' style="color: var(--teal); font-size: 1.1rem;"></i> Garansi Tukar Ukuran
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--slate); font-weight: 500;">
                                    <i class='bx bx-support' style="color: var(--teal); font-size: 1.1rem;"></i> Bantuan 24/7
                                </div>
                            </div>
                        </div>
                        <div class="hero-img-wrap" style="text-align: center; position: relative;">
                            <div style="position: absolute; inset: 8% 12%; background: var(--fog); border-radius: 50%; z-index: 0;"></div>
                            <img src="{{ asset($hero->image) }}" alt="{{ $hero->name }}" class="home__img" style="position: relative; z-index: 1;">
                            <span style="position: absolute; top: 10px; right: 10px; z-index: 2; background: var(--white); color: var(--ink); border: 1px solid var(--mist); font-weight: 600; font-size: 0.75rem; padding: 6px 12px; border-radius: var(--radius-sm); box-shadow: var(--shadow-sm);">
                                Sertifikasi SNI
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br>
    </section>

    <!-- Slide controls -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <div style="text-align:center; margin-bottom: 30px;">
        @foreach($heroProducts as $index => $hero)
            <span class="dots" onclick="currentSlide({{ $index + 1 }})"></span>
        @endforeach
    </div>

    <!--========== TRUST VALUE PROPOSITION ==========-->
    <div class="bd-container">
        <div class="trust-banner">
            <div class="trust-card">
                <div class="trust-icon">
                    <i class='bx bx-check-shield'></i>
                </div>
                <div>
                    <div class="trust-title">SNI Resmi & Asli</div>
                    <div class="trust-desc">Standar keamanan teruji & tersertifikasi</div>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon">
                    <i class='bx bx-package'></i>
                </div>
                <div>
                    <div class="trust-title">Pengiriman Aman</div>
                    <div class="trust-desc">Packing kardus tebal & bubble wrap</div>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon">
                    <i class='bx bx-sync'></i>
                </div>
                <div>
                    <div class="trust-title">Garansi Tukar Ukuran</div>
                    <div class="trust-desc">Bebas tukar ukuran bila tidak pas</div>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon">
                    <i class='bx bx-support'></i>
                </div>
                <div>
                    <div class="trust-title">Bantuan Ramah 24/7</div>
                    <div class="trust-desc">Konsultasi helm langsung via WhatsApp</div>
                </div>
            </div>
        </div>
    </div>

    <!--========== PRODUCTS WEEKLY SECTION ==========-->
    <section class="products section bd-container" id="products">
        <div style="text-align: left; margin-bottom: 36px;">
            <span class="section-subtitle" style="font-weight: 600; color: var(--teal);">Pilihan terbaik minggu ini</span>
            <h2 class="section-title" style="margin-top: 4px;">Koleksi helm populer</h2>
            <p style="max-width: 550px; margin: 8px 0 0; color: var(--slate); font-size: 0.95rem;">
                Dibuat dengan bahan ABS thermoplastic berkualitas, interior empuk yang dapat dicuci, dan sistem ventilasi optimal.
            </p>
        </div>

        <div class="products__container bd-grid logo-slider slick_two">
            @foreach($products as $prod)
                <div class="product-card-modern">
                    <div class="product-img-box">
                        <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}">
                    </div>
                    <span class="product-cat">{{ $prod->category }}</span>
                    <h3 class="product-title">{{ $prod->name }}</h3>
                    
                    <div style="display: flex; align-items: center; gap: 4px; color: #f59e0b; font-size: 0.82rem; margin-bottom: 10px;">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <span style="color: #64748b; margin-left: 4px;">(5.0)</span>
                    </div>

                    <div class="product-price">{{ $prod->formatted_price }}</div>

                    <div class="product-actions">
                        <a href="{{ route('product.show', $prod->slug) }}" class="btn-detail-link">
                            Detail Produk
                        </a>
                        <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $prod->id }}">
                            <button type="submit" class="btn-quick-cart" title="Tambah Cepat ke Keranjang">
                                <i class='bx bx-cart-add'></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!--========== ABOUT ==========-->
    <section class="about section bd-container" id="about">
        <div class="about__container bd-grid" style="align-items: center;">
            <div class="about__data">
                <span class="section-subtitle about__initial">Tentang Fan Helmet</span>
                <h2 class="section-title about__initial">Dedikasi Untuk Keselamatan & Gaya Berkendara Anda</h2>
                <p class="about__description" style="line-height: 1.7;">
                    Fan Helmet didirikan dengan misi menghadirkan helm berstandar SNI kelas dunia tanpa mengorbankan estetika dan kenyamanan. Setiap helm kami melewati uji ketahanan benturan dan proses pengecatan multi-lapis presisi tinggi agar tahan terhadap cuaca ekstrem.
                </p>
                <div style="margin-top: 24px; display: flex; gap: 32px;">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 600; color: var(--ink);">10.000+</div>
                        <div style="font-size: 0.82rem; color: var(--slate);">Riders puas</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 600; color: var(--ink);">100%</div>
                        <div style="font-size: 0.82rem; color: var(--slate);">Lolos uji SNI</div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('assets/img/about.jpg') }}" alt="Tentang Fan Helmet" class="about__img" style="border-radius: var(--radius-md); border: 1px solid var(--mist);">
        </div>
    </section>

    <!--========== SERVICES / KEUNGGULAN ==========-->
    <section class="services section bd-container" id="services">
        <div style="text-align: left; margin-bottom: 36px;">
            <span class="section-subtitle" style="font-weight: 600; color: var(--teal);">Keunggulan produk kami</span>
            <h2 class="section-title">Standar kualitas tanpa kompromi</h2>
        </div>

        <div class="services__container bd-grid">
            <div class="services__content" style="background: var(--white); border-radius: var(--radius-md); padding: 26px; border: 1px solid var(--mist); text-align: left;">
                <img src="{{ asset('assets/img/helmet-upper.jpeg') }}" alt="Pengecatan" class="services__img" style="border-radius: var(--radius-sm); margin-bottom: 16px;">
                <h3 class="services__title">Pengecatan ultra-glossy & doff</h3>
                <p class="services__description">Cat polyurethane premium tahan gores dan tidak mudah pudar terkena terik matahari.</p>
            </div>

            <div class="services__content" style="background: var(--white); border-radius: var(--radius-md); padding: 26px; border: 1px solid var(--mist); text-align: left;">
                <img src="{{ asset('assets/img/helmet-visor.jpg') }}" alt="Pemasangan" class="services__img" style="border-radius: var(--radius-sm); margin-bottom: 16px;">
                <h3 class="services__title">Visor anti-scratch & UV protection</h3>
                <p class="services__description">Pandangan jernih tanpa distorsi optik, melindungi mata dari silau dan serangga.</p>
            </div>

            <div class="services__content" style="background: var(--white); border-radius: var(--radius-md); padding: 26px; border: 1px solid var(--mist); text-align: left;">
                <img src="{{ asset('assets/img/helmet-design.jpg') }}" alt="SNI" class="services__img" style="border-radius: var(--radius-sm); margin-bottom: 16px;">
                <h3 class="services__title">Sertifikasi SNI 1811-2007</h3>
                <p class="services__description">Telah lolos uji ketahanan penetrasi dan pelepasan tali pengikat standar nasional.</p>
            </div>
        </div>
    </section>

    <!--===== SIZE CHART =======-->
    <section class="size section bd-container" id="size">
        <div class="size__container bd-grid" style="align-items: center;">
            <div class="size__data">
                <span class="section-subtitle size__initial">Panduan Ukuran Helm</span>
                <h2 class="section-title size__initial">Cari Ukuran Helm Yang Pas di Kepala Anda</h2>
                <p class="size__description" style="line-height: 1.7;">
                    Helm yang terlalu longgar akan berbahaya saat benturan, sedangkan helm yang terlalu sempit akan membuat kepala pusing. Ukur lingkar kepala Anda tepat di atas alis menggunakan meteran kain dan cocokkan dengan tabel ukuran di samping.
                </p>
            </div>
            <div style="text-align: center;">
                <img src="{{ asset('assets/img/size-chart.png') }}" alt="Size Chart" class="size__img" style="border-radius: var(--radius-md); background: var(--white); padding: 12px; border: 1px solid var(--mist);">
            </div>
        </div>
    </section>

    <!--========== CONTACT CTA ==========-->
    <section class="contact section bd-container" id="contact" style="margin-bottom: 40px;">
        <div class="contact__container bd-grid" style="background: var(--ink); border-radius: var(--radius-lg); padding: 48px 40px; color: #fff; align-items: center;">
            <div class="contact__data">
                <span style="color: var(--amber); font-weight: 600; font-size: 0.9rem;">Butuh bantuan memilih helm?</span>
                <h2 style="color: #fff; font-family: var(--font-display); font-size: 1.7rem; font-weight: 600; margin: 8px 0 12px;">Konsultasikan langsung dengan tim ahli kami</h2>
                <p style="color: #B8BDC1; font-size: 0.95rem; line-height: 1.6;">Kami siap membantu merekomendasikan varian helm dan ukuran yang paling pas untuk model motor dan gaya berkendara Anda.</p>
            </div>

            <div class="contact__button" style="text-align: right;">
                <a href="{{ route('contact.index') }}" class="button" style="background: var(--white); color: var(--ink); padding: 13px 26px; font-size: 0.95rem;">
                    Hubungi kami sekarang
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        if ($('.slick_two').length) {
            $('.slick_two').slick({
                dots: true,
                arrows: true,                
                infinite: true,
                autoplay: true,
                speed: 1200,
                autoplaySpeed: 3000,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }
    });
</script>
@endpush