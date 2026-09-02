<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Redirecting to 3D Secure — Marmaris Travel Center') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f4f7fb 0%, #e8f2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .redirect-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            padding: 48px;
            text-align: center;
            max-width: 420px;
            width: 90%;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #0066cc;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 24px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h2 { font-size: 20px; color: #0b1d33; margin-bottom: 8px; }
        p { color: #64748b; font-size: 14px; line-height: 1.6; }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
    @include('front.partials.rtl')
</head>
<body>
    <div class="redirect-card">
        <div class="spinner"></div>
        <h2>{{ __('Redirecting to 3D Secure') }}</h2>
        <p>You are being redirected to your bank's secure authentication page. Please do not close this window.</p>
    </div>

    <form id="threeDForm" action="{{ $gatewayUrl }}" method="POST" accept-charset="ISO-8859-9" style="display:none;">
        @foreach($params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <noscript>
            <button type="submit" style="display:block;margin:20px auto;padding:12px 24px;background:#0066cc;color:#fff;border:0;border-radius:8px;cursor:pointer;">{{ __('Continue to 3D Secure') }}</button>
        </noscript>
    </form>

    <script>
        document.getElementById('threeDForm').submit();
    </script>
</body>
</html>
