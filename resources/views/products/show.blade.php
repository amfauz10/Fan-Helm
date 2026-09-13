@extends('layouts.app')

@section('title', $product->name . ' - Detail Helm Fan Helmet')

@section('content')
    <style>
        .product-detail-wrapper {
            padding-top: calc(var(--header-height) + 1.5rem);
            padding-bottom: 60px;
        }
        .detail-card {
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px solid var(--mist);
            padding: 36px;
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 40px;
            align-items: start;
        }
        @media (max-width: 860px) {
            .detail-card {
                grid-template-columns: 1fr;
                padding: 24px;
            }
        }
        .main-img-holder {
            width: 100%;
            height: 380px;
            background: var(--fog);
            border-radius: var(--radius-md);
            border: 1px solid var(--mist);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .main-img-holder img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .thumbs-grid {
            display: flex;
            gap: 12px;
        }
        .thumb-box {
            width: 75px;
            height: 75px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--mist);
            background: var(--fog);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 6px;
            transition: border-color 0.15s;
        }
        .thumb-box.active {
            border-color: var(--teal);
        }
        .thumb-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* PILIHAN UKURAN */
        .size-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            height: 42px;
            padding: 0 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--mist);
            background: var(--white);
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--ink);
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }
        .size-pill:hover {
            border-color: var(--slate-soft);
            background: var(--fog);
        }
        .size-pill.active {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--success-wash);
            color: var(--success);
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>

    <div class="bd-container product-detail-wrapper">
        <!-- BREADCRUMB -->
        <div style="margin-bottom: 24px; font-size: 0.88rem; color: var(--slate);">
            <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;"><i class='bx bxs-home'></i> Beranda</a>
            <span style="margin: 0 8px;">/</span>
            <a href="{{ route('home') }}#products" style="color: inherit; text-decoration: none;">Produk</a>
            <span style="margin: 0 8px;">/</span>
            <span style="color: var(--ink); font-weight: 600;">{{ $product->name }}</span>
        </div>

        <div class="detail-card">
            <!-- GALLERY -->
            <div>
                <div class="main-img-holder">
                    <img id="productBigImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="thumbs-grid">
                    <div class="thumb-box active" onclick="switchThumb('{{ asset($product->image) }}', this)">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    </div>
                    @if(!empty($product->gallery_images))
                        @foreach($product->gallery_images as $gImg)
                            <div class="thumb-box" onclick="switchThumb('{{ asset($gImg) }}', this)">
                                <img src="{{ asset($gImg) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- PRODUCT INFO & BUY FORM -->
            <div>
                <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items: center;">
                    <span class="badge badge-secondary" style="font-size: 0.85rem; padding: 4px 12px;">{{ $product->category }}</span>
                    <span class="info-pill"><i class='bx bx-check-circle'></i> Standar SNI Resmi</span>
                </div>

                <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin-bottom: 8px;">
                    {{ $product->name }}
                </h1>

                @if($product->subtitle)
                    <p style="font-size: 1.05rem; color: var(--slate); margin-bottom: 18px; font-weight: 400;">
                        {{ $product->subtitle }}
                    </p>
                @endif

                <div style="font-family: var(--font-display); font-size: 1.85rem; font-weight: 600; color: var(--ink); margin-bottom: 24px;">
                    {{ $product->formatted_price }}
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="size" id="selectedSizeInput" value="{{ $product->sizes[0] ?? 'L' }}">

                    <!-- SIZE SELECTION -->
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="font-weight: 600; font-size: 0.92rem; color: var(--ink);">
                                Pilih ukuran: <span id="currentSizeLabel" style="color: var(--teal);">{{ $product->sizes[0] ?? 'L' }}</span>
                            </label>
                            <a href="{{ route('home') }}#size" style="font-size: 0.82rem; color: var(--teal); font-weight: 600;">
                                <i class='bx bx-ruler'></i> Panduan ukuran
                            </a>
                        </div>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            @if(!empty($product->sizes))
                                @foreach($product->sizes as $idx => $size)
                                    <div class="size-pill {{ $idx === 0 ? 'active' : '' }}" onclick="selectSize('{{ $size }}', this)">
                                        {{ $size }}
                                    </div>
                                @endforeach
                            @else
                                <div class="size-pill active" onclick="selectSize('All Size', this)">All Size</div>
                            @endif
                        </div>
                    </div>

                    <!-- QUANTITY -->
                    <div style="margin-bottom: 28px;">
                        <label style="display: block; font-weight: 600; font-size: 0.92rem; color: var(--ink); margin-bottom: 10px;">
                            Jumlah helm
                        </label>
                        <div style="display: inline-flex; align-items: center; border: 1px solid var(--mist); border-radius: var(--radius-sm); overflow: hidden; background: var(--white);">
                            <button type="button" onclick="adjustQty(-1)" style="width: 40px; height: 40px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--slate);">-</button>
                            <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="50" style="width: 50px; text-align: center; border: none; font-weight: 600; font-size: 1rem; outline: none;">
                            <button type="button" onclick="adjustQty(1)" style="width: 40px; height: 40px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--slate);">+</button>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div style="display: flex; gap: 14px; margin-bottom: 36px;">
                        <button type="submit" class="button" style="flex: 1; justify-content: center; padding: 13px; font-size: 1rem; border-radius: var(--radius-sm); border: none; cursor: pointer;">
                            <i class='bx bx-cart-add' style="font-size: 1.3rem; margin-right: 6px;"></i> Tambahkan ke keranjang
                        </button>
                    </div>
                </form>

                <!-- SPECS & MATERIAL BOX -->
                <div style="border-top: 1px solid var(--mist); padding-top: 24px;">
                    <div style="margin-bottom: 16px;">
                        <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--ink); margin-bottom: 6px;">
                            <i class='bx bx-wrench' style="color: var(--teal);"></i> Spesifikasi teknis
                        </h4>
                        <p style="font-size: 0.9rem; color: var(--slate); line-height: 1.6;">
                            {!! nl2br(e($product->specification ?? 'Standar SNI, Visor Scratch-Resistant, Busa dapat dilepas')) !!}
                        </p>
                    </div>

                    @if($product->materials)
                        <div>
                            <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--ink); margin-bottom: 6px;">
                                <i class='bx bx-shield' style="color: var(--teal);"></i> Bahan material
                            </h4>
                            <p style="font-size: 0.9rem; color: var(--slate); line-height: 1.6;">
                                {{ $product->materials }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RELATED PRODUCTS -->
        @if($relatedProducts->count() > 0)
            <div style="margin-top: 60px;">
                <h3 style="font-family: var(--font-display); font-size: 1.4rem; font-weight: 600; color: var(--ink); margin-bottom: 24px;">
                    Helm lain yang mungkin Anda suka
                </h3>
                <div class="products__container bd-grid logo-slider slick_two">
                    @foreach($relatedProducts as $rel)
                        <div class="product-card-modern">
                            <div class="product-img-box">
                                <img src="{{ asset($rel->image) }}" alt="{{ $rel->name }}">
                            </div>
                            <span class="product-cat">{{ $rel->category }}</span>
                            <h4 class="product-title">{{ $rel->name }}</h4>
                            <div class="product-price">{{ $rel->formatted_price }}</div>
                            <div class="product-actions">
                                <a href="{{ route('product.show', $rel->slug) }}" class="btn-detail-link">
                                    Lihat Helm
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function switchThumb(imgUrl, el) {
        document.querySelectorAll('.thumb-box').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('productBigImg').src = imgUrl;
    }

    function selectSize(size, el) {
        document.querySelectorAll('.size-pill').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('selectedSizeInput').value = size;
        document.getElementById('currentSizeLabel').innerText = size;
    }

    function adjustQty(amount) {
        var input = document.getElementById('quantityInput');
        var val = parseInt(input.value) || 1;
        val += amount;
        if (val < 1) val = 1;
        if (val > 50) val = 50;
        input.value = val;
    }
</script>
@endpush
