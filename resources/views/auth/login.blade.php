<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Akun - Fan Helm Official Store</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root {
            --primary: #1B1E21;
            --primary-dark: #2B6660;
            --ink: #1B1E21;
            --muted: #565C63;
            --line: #E1E4E2;
            --fog: #F1F3F1;
            --slate-soft: #8A9096;
            --radius-sm: 6px;
            --radius-md: 10px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--fog);
            padding: 24px;
        }

        .auth-shell {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 40px 36px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 22px;
        }
        .brand .icon-box {
            width: 40px; height: 40px; border-radius: var(--radius-sm);
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.3rem;
        }
        .brand span { font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 1.2rem; color: var(--ink); letter-spacing: -.2px; }

        h1 { font-family: 'Space Grotesk', sans-serif; text-align: center; font-size: 1.5rem; font-weight: 600; color: var(--ink); }
        .sub { text-align: center; color: var(--muted); font-size: .92rem; margin: 6px 0 26px; }

        .alert-auth {
            background: #FBEAE6; border: 1px solid #E8BEB2; border-radius: var(--radius-sm);
            padding: 12px 14px; margin-bottom: 18px; display: flex; gap: 8px;
            align-items: center; color: #B23B26; font-size: .85rem; font-weight: 500;
        }
        .alert-ok {
            background: #E8F3EC; border: 1px solid #BEDBC9; color: #2F7A4D;
        }

        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: .85rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        .input-box { position: relative; display: flex; align-items: center; }
        .input-box i { position: absolute; left: 13px; color: var(--slate-soft); font-size: 1.15rem; }
        .input-box input {
            width: 100%; padding: 12px 42px; border: 1px solid var(--line); border-radius: var(--radius-sm);
            font-size: .93rem; color: var(--ink); background: var(--fog); outline: none; transition: .15s;
        }
        .input-box input:focus { background: #fff; border-color: var(--primary-dark); }
        .toggle-pw { right: 13px; left: auto !important; cursor: pointer; }

        .row-between { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 22px; font-size: .85rem; }
        .remember { display: flex; align-items: center; gap: 7px; color: var(--muted); cursor: pointer; }
        .remember input { accent-color: var(--primary-dark); width: 16px; height: 16px; }

        .btn-submit {
            width: 100%; padding: 13px; border: none; border-radius: var(--radius-sm); cursor: pointer;
            background: var(--primary); color: #fff;
            font-weight: 600; font-size: .95rem; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .15s;
        }
        .btn-submit:hover { background: var(--primary-dark); }

        .footer-line { text-align: center; margin-top: 22px; font-size: .88rem; color: var(--muted); }
        .footer-line a { color: var(--primary-dark); font-weight: 600; text-decoration: none; }

        .back-home { display: block; text-align: center; margin-top: 14px; font-size: .82rem; color: var(--slate-soft); text-decoration: none; }
        .back-home:hover { color: var(--muted); }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="brand">
            <div class="icon-box"><i class='bx bxs-shield'></i></div>
            <span>Fan Helm</span>
        </div>
        <h1>Masuk ke Akun Anda</h1>
        <p class="sub">Belanja helm premium, lacak pesanan, atau kelola toko.</p>

        @if ($errors->any())
            <div class="alert-auth">
                <i class='bx bx-error-circle' style="font-size: 1.2rem;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-auth"><i class='bx bx-error-circle' style="font-size: 1.2rem;"></i><span>{{ session('error') }}</span></div>
        @endif
        @if (session('success'))
            <div class="alert-auth alert-ok"><i class='bx bx-check-circle' style="font-size: 1.2rem;"></i><span>{{ session('success') }}</span></div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="field">
                <label for="email">Alamat Email</label>
                <div class="input-box">
                    <i class='bx bx-envelope'></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <i class='bx bx-show toggle-pw' id="togglePasswordBtn" onclick="togglePasswordVisibility()"></i>
                </div>
            </div>

            <div class="row-between">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <span>Masuk</span>
                <i class='bx bx-right-arrow-alt' style="font-size: 1.2rem;"></i>
            </button>
        </form>

        <p class="footer-line">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        <a href="{{ route('home') }}" class="back-home"><i class='bx bx-arrow-back'></i> Kembali ke Beranda Toko</a>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const btn = document.getElementById('togglePasswordBtn');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('bx-show', !show);
            btn.classList.toggle('bx-hide', show);
        }
    </script>
</body>
</html>