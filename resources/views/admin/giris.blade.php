<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş — Travel Center Marmaris</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('tema/img/favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bh-primary: #0066cc;
            --bh-secondary: #ff6b00;
            --bh-dark: #0b1d33;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
        }
        .login-left {
            width: 45%;
            background: linear-gradient(135deg, var(--bh-dark), #162d4f);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(0,102,204,0.08);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,107,0,0.06);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            z-index: 1;
        }
        .login-brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--bh-secondary), #ff8c33);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
        }
        .login-brand-text {
            font-size: 24px;
            font-weight: 400;
        }
        .login-brand-text strong {
            font-weight: 800;
            color: var(--bh-secondary);
        }
        .login-left h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
            z-index: 1;
        }
        .login-left p {
            font-size: 15px;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
            max-width: 360px;
            text-align: center;
            z-index: 1;
        }
        .login-features {
            margin-top: 40px;
            z-index: 1;
        }
        .login-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-bottom: 14px;
        }
        .login-features li i {
            color: var(--bh-secondary);
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        .login-right {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            background: #f8fafc;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
        }
        .login-card h3 {
            font-size: 26px;
            font-weight: 800;
            color: var(--bh-dark);
            margin-bottom: 6px;
        }
        .login-card .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--bh-dark);
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
        }
        .input-wrap input {
            width: 100%;
            height: 52px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 16px 0 46px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: var(--bh-dark);
            transition: all 0.2s;
            background: #fff;
        }
        .input-wrap input:focus {
            border-color: var(--bh-primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(0,102,204,0.1);
        }
        .btn-login {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 10px;
            background: var(--bh-primary);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            background: #004999;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,102,204,0.3);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        .back-link a {
            color: #64748b;
            font-size: 14px;
            text-decoration: none;
        }
        .back-link a:hover { color: var(--bh-primary); }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <div class="login-left">
        <div class="login-brand">
            <span class="login-brand-icon"><i class="fas fa-sun"></i></span>
            <span class="login-brand-text">Travel Center <strong>Marmaris</strong></span>
        </div>
        <h2>Yönetim Paneli</h2>
        <p>Transfer rezervasyonlarınızı, aktivite satışlarınızı ve müşteri verilerinizi tek yerden yönetin.</p>
        <ul class="login-features">
            <li><i class="fas fa-shuttle-van"></i> Transfer Rezervasyon Yönetimi</li>
            <li><i class="fas fa-star"></i> Aktivite Satış Takibi</li>
            <li><i class="fas fa-users"></i> Müşteri CRM ve Dışa Aktarma</li>
            <li><i class="fas fa-images"></i> Slider ve İçerik Yönetimi</li>
        </ul>
    </div>
    <div class="login-right">
        <div class="login-card">
            <h3>Hoş geldiniz</h3>
            <p class="subtitle">Yönetim hesabınıza giriş yapın</p>

            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.giris.post') }}">
                @csrf
                <div class="form-group">
                    <label>Kullanıcı Adı</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="kullanici_adi" placeholder="Kullanıcı adınızı girin" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label>Şifre</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="sifre" placeholder="Şifrenizi girin" required>
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </button>
                <div class="back-link">
                    <a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Siteye dön</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
