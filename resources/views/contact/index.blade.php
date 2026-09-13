@extends('layouts.app')

@section('title', 'Hubungi Kami - Fan Helm')

@section('content')
    <style>
        .contact-hero {
            background: linear-gradient(135deg, var(--accent-dark) 0%, #1a1a22 100%);
            padding-top: calc(var(--header-height) + 3.5rem);
            padding-bottom: 3rem;
            text-align: center;
            color: #fff;
        }
        .contact-hero h1 {
            font-size: 2.1rem;
            font-weight: 600;
            margin: 10px 0 8px;
        }
        .contact-hero p {
            color: var(--mist);
            font-size: 0.95rem;
            max-width: 480px;
            margin: 0 auto;
        }

        .contact-wrap {
            max-width: 1080px;
            margin: -2.5rem auto 4rem;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 860px) {
            .contact-wrap {
                grid-template-columns: 0.85fr 1.15fr;
                align-items: start;
            }
        }

        /* INFO CARD */
        .contact-info-card {
            background: var(--accent-dark);
            border-radius: var(--radius-md);
            padding: 36px 30px;
            color: #fff;
            box-shadow: 0 20px 45px rgba(0,0,0,0.25);
        }
        .contact-info-card h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .contact-info-card > p {
            color: var(--slate-soft);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 22px;
        }
        .contact-info-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: var(--radius-sm);
            background: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .contact-info-item strong {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }
        .contact-info-item span {
            font-size: 0.85rem;
            color: var(--slate-soft);
            line-height: 1.5;
        }
        .contact-social {
            display: flex;
            gap: 10px;
            margin-top: 28px;
        }
        .contact-social a {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.08);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.2s;
        }
        .contact-social a:hover {
            background: var(--first-color);
        }

        /* FORM CARD */
        .contact-form-card {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 36px 34px;
            border: 1px solid var(--mist);
            box-shadow: none;
        }
        .contact-form-card h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--title-color);
            margin-bottom: 20px;
        }
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        @media (min-width: 520px) {
            .form-row-2 { grid-template-columns: 1fr 1fr; }
        }
        .form-field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }
        .form-field input,
        .form-field textarea {
            width: 100%;
            border: 1px solid var(--mist);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--title-color);
            background: var(--fog);
            transition: border-color 0.2s, background 0.2s;
        }
        .form-field input:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: var(--first-color);
            background: #fff;
        }
        .form-field textarea {
            min-height: 120px;
            resize: vertical;
        }
        .contact-submit-btn {
            width: 100%;
            border: none;
            cursor: pointer;
        }

        .contact-back {
            text-align: center;
            margin-top: -20px;
            margin-bottom: 50px;
        }
        .contact-back a {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--slate);
            text-decoration: none;
        }
        .contact-back a:hover {
            color: var(--first-color);
        }
    </style>

    <!--========== HERO ==========-->
    <section class="contact-hero">
        <span class="section-subtitle" style="background: rgba(255,255,255,0.1); color: var(--accent-yellow);">Kami Siap Membantu</span>
        <h1>Hubungi Fan Helmet</h1>
        <p>Ada pertanyaan soal produk, ukuran, atau pesanan Anda? Tim kami siap merespons secepat mungkin.</p>
    </section>

    <!--========== CONTENT ==========-->
    <div class="contact-wrap">
        <!-- INFO CARD -->
        <div class="contact-info-card">
            <h2>Info Kontak</h2>
            <p>Kunjungi toko kami atau hubungi langsung lewat kontak di bawah ini untuk respons lebih cepat.</p>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class='bx bx-map-pin'></i></div>
                <div>
                    <strong>Alamat Toko</strong>
                    <span>Jl. Eyang Weri, Kuningan 45511</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class='bx bx-phone'></i></div>
                <div>
                    <strong>Telepon</strong>
                    <span>+62-821-1807-9547</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon" style="background: #1F8A5C;"><i class='bx bxl-whatsapp'></i></div>
                <div>
                    <strong>WhatsApp</strong>
                    <span>0821-1807-9547 (Chat 24/7)</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon"><i class='bx bx-envelope'></i></div>
                <div>
                    <strong>Email</strong>
                    <span>support@fanhelm.com</span>
                </div>
            </div>

            <div class="contact-social">
                <a href="#"><i class='bx bxl-facebook'></i></a>
                <a href="#"><i class='bx bxl-instagram'></i></a>
                <a href="#"><i class='bx bxl-twitter'></i></a>
            </div>
        </div>

        <!-- FORM CARD -->
        <div class="contact-form-card">
            <h3>Kirim Pesan ke Kami</h3>

            @if(session('success'))
                <div class="flash-alert flash-success" style="position: static; margin-bottom: 20px; box-shadow: none;">
                    <i class='bx bx-check-circle' style="font-size: 1.3rem; color: #059669;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="flash-alert flash-error" style="position: static; margin-bottom: 20px; box-shadow: none; display: block;">
                    <strong>Perhatian:</strong>
                    <ul style="margin: 6px 0 0 18px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-row-2">
                    <div class="form-field">
                        <label for="firstName">Nama Depan</label>
                        <input type="text" name="firstName" id="firstName" value="{{ old('firstName') }}" placeholder="cth. Budi" required>
                    </div>
                    <div class="form-field">
                        <label for="lastName">Nama Belakang</label>
                        <input type="text" name="lastName" id="lastName" value="{{ old('lastName') }}" placeholder="cth. Santoso" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                    <div class="form-field">
                        <label for="mobile">No Telepon</label>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="08xx-xxxx-xxxx" required>
                    </div>
                </div>

                <div class="form-field" style="margin-bottom: 20px;">
                    <label for="message">Pesan</label>
                    <textarea name="message" id="message" placeholder="Tuliskan pertanyaan atau kebutuhan Anda di sini..." required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-energy contact-submit-btn">
                    Kirim Pesan <i class='bx bx-send'></i>
                </button>
            </form>
        </div>
    </div>

    <div class="contact-back">
        <a href="{{ route('home') }}">&larr; Kembali Ke Beranda</a>
    </div>
@endsection