<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful — Travel Center Marmaris</title>
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
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
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
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #fff;
            margin-bottom: 24px;
            animation: scaleIn 0.5s ease;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            60% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
        h1 { font-size: 24px; font-weight: 800; color: var(--bh-dark); margin-bottom: 8px; }
        .subtitle { color: #64748b; font-size: 14px; margin-bottom: 28px; line-height: 1.6; }
        .detail-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            margin-bottom: 28px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #dcfce7;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; }
        .detail-value { color: var(--bh-dark); font-weight: 600; }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bh-primary);
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #004999;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,102,204,0.3);
        }
    </style>
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
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h1>Payment Successful!</h1>
            <p class="subtitle">Your payment has been processed successfully. A confirmation email will be sent to your email address.</p>

            <div class="detail-box">
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Customer</span>
                    <span class="detail-value">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                </div>
                @if($payment)
                <div class="detail-row">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value" style="color:#059669;">&pound;{{ number_format($payment->amount, 0) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Card</span>
                    <span class="detail-value">**** {{ $payment->card_last4 }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color:#059669;"><i class="fas fa-check-circle"></i> Paid</span>
                </div>
            </div>

            <a href="{{ route('anasayfa') }}" class="btn-home">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
