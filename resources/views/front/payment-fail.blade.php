<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed — Marmaris Travel Center</title>
    @if(!empty($ayar->favicon))
    <link rel="icon" href="{{ asset('tema/uploads/' . $ayar->favicon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bh-primary: {{ $ayar->renk1 ?? '#0066cc' }};
            --bh-secondary: {{ $ayar->renk2 ?? '#ff6b00' }};
            --bh-dark: {{ $ayar->renk3 ?? '#0b1d33' }};
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        [data-theme="dark"] body { background: #0b1220 !important; }
        [data-theme="dark"] .result-card { background: #1a2332 !important; color: #e2e8f0; }
        [data-theme="dark"] .result-card h1, [data-theme="dark"] .result-card h2, [data-theme="dark"] .result-card p { color: #e2e8f0 !important; }
        .result-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            max-width: 520px;
            width: 100%;
            overflow: hidden;
        }
        .result-header {
            background: var(--bh-dark);
            padding: 24px 30px;
        }
        .result-header .logo {
            font-size: 18px;
            color: #fff;
            font-weight: 400;
        }
        .result-header .logo strong {
            font-weight: 800;
            color: var(--bh-secondary);
        }
        .result-body {
            padding: 40px 36px;
            text-align: center;
        }
        .fail-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #fff;
            margin-bottom: 24px;
        }
        h1 { font-size: 24px; font-weight: 800; color: var(--bh-dark); margin-bottom: 8px; }
        .subtitle { color: #64748b; font-size: 14px; margin-bottom: 28px; line-height: 1.6; }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 28px;
            font-size: 13px;
            color: #dc2626;
        }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bh-secondary);
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s ease;
            margin-right: 12px;
        }
        .btn-retry:hover {
            background: #e05500;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,107,0,0.35);
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            margin-top: 16px;
        }
        .btn-home:hover { color: var(--bh-dark); }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
</head>
<body>
    <div class="result-card">
        <div class="result-header">
            <div class="logo">
                @if(!empty($ayar->firma_logo))
                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="" style="max-height:36px;">
                @else
                    <i class="fas fa-sun" style="color:var(--bh-secondary);margin-right:8px;"></i> Travel Center <strong>Marmaris</strong>
                @endif
            </div>
        </div>
        <div class="result-body">
            <div class="fail-icon"><i class="fas fa-times"></i></div>
            <h1>Payment Failed</h1>
            <p class="subtitle">Unfortunately, your payment could not be processed. Your card has not been charged.</p>

            @if($payment && $payment->error_message)
            <div class="error-box">
                <i class="fas fa-exclamation-triangle" style="margin-right:4px;"></i>
                {{ $payment->error_message }}
            </div>
            @endif

            <a href="{{ route('payment.garanti', $customer->id) }}" class="btn-retry">
                <i class="fas fa-redo"></i> Try Again
            </a>
            <br>
            <a href="{{ route('anasayfa') }}" class="btn-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</body>
</html>
