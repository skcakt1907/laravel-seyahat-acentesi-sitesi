<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed — Marmaris Travel Center</title>
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bh-primary: #0066cc;
            --bh-secondary: #ff6b00;
            --bh-dark: #0b1d33;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f4f7fb 0%, #e8f2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        [data-theme="dark"] body { background: #0b1220 !important; }
        [data-theme="dark"] .confirm-card { background: #1a2332; }
        [data-theme="dark"] .confirm-body h1 { color: #ffffff; }
        [data-theme="dark"] .confirm-body .subtitle { color: #94a3b8; }
        [data-theme="dark"] .confirm-details { background: #0f1825; }
        [data-theme="dark"] .confirm-details .detail-row { border-bottom-color: #2a3548; color: #cbd5e1; }
        .confirm-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            max-width: 580px;
            width: 100%;
            overflow: hidden;
        }
        .confirm-header {
            background: var(--bh-dark);
            padding: 24px 30px;
            text-align: center;
        }
        .confirm-header .logo {
            font-size: 20px;
            color: #fff;
            font-weight: 400;
        }
        .confirm-header .logo strong {
            font-weight: 800;
            color: var(--bh-secondary);
        }
        .confirm-body {
            padding: 36px;
            text-align: center;
        }
        .confirm-icon {
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
        .confirm-body h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--bh-dark);
            margin-bottom: 8px;
        }
        .confirm-body .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .confirm-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            margin-bottom: 20px;
        }
        .confirm-details .section-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--bh-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .confirm-details .section-label:first-child { margin-top: 0; }
        .confirm-details .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .confirm-details .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 500; }
        .detail-value { color: var(--bh-dark); font-weight: 600; text-align: right; max-width: 60%; }
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
            text-decoration: none;
        }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
</head>
<body>
    <div class="confirm-card">
        <div class="confirm-header">
            <div class="logo"><i class="fas fa-sun" style="color:var(--bh-secondary);margin-right:8px;"></i> Travel Center <strong>Marmaris</strong></div>
        </div>
        <div class="confirm-body">
            <div class="confirm-icon"><i class="fas fa-check"></i></div>
            <h1>Reservation Confirmed!</h1>
            <p class="subtitle">Thank you, your reservation has been received successfully. We will contact you shortly.</p>

            <div class="confirm-details">
                <div class="section-label"><i class="fas fa-user"></i> Personal Details</div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $customer->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">{{ $customer->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>

                @if($customer->package)
                <div class="section-label"><i class="fas fa-shuttle-van"></i> Transfer Details</div>
                <div class="detail-row">
                    <span class="detail-label">Route</span>
                    <span class="detail-value">{{ $customer->package }}</span>
                </div>
                @endif

                @if($customer->hotel_name)
                <div class="detail-row">
                    <span class="detail-label">Hotel</span>
                    <span class="detail-value">{{ $customer->hotel_name }}</span>
                </div>
                @endif

                @if($customer->adult_count)
                <div class="detail-row">
                    <span class="detail-label">Adults</span>
                    <span class="detail-value">{{ $customer->adult_count }}{{ $customer->adult_names ? ' — ' . $customer->adult_names : '' }}</span>
                </div>
                @endif

                @if($customer->child_count && $customer->child_count > 0)
                <div class="detail-row">
                    <span class="detail-label">Children</span>
                    <span class="detail-value">{{ $customer->child_count }}{{ $customer->child_names ? ' — ' . $customer->child_names : '' }}</span>
                </div>
                @endif

                @if($customer->arrival_date)
                <div class="section-label"><i class="fas fa-plane-arrival"></i> Arrival</div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($customer->arrival_date)->format('d M Y') }} at {{ $customer->arrival_time }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Flight No</span>
                    <span class="detail-value">{{ $customer->arrival_flight }}</span>
                </div>
                @endif

                @if($customer->departure_date)
                <div class="section-label"><i class="fas fa-plane-departure"></i> Departure</div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($customer->departure_date)->format('d M Y') }} at {{ $customer->departure_time }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Flight No</span>
                    <span class="detail-value">{{ $customer->departure_flight }}</span>
                </div>
                @endif

                @if($customer->notes)
                <div class="section-label"><i class="fas fa-comment-dots"></i> Notes</div>
                <div style="font-size:13px;color:#334155;line-height:1.6;padding:4px 0;">{{ $customer->notes }}</div>
                @endif
            </div>

            <a href="{{ route('anasayfa') }}" class="btn-home">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
