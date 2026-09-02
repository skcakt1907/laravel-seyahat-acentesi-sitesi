<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Reservation Confirmed — Marmaris Travel Center') }}</title>
    @include('front.partials.gtag')
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bh-primary: #0066cc;
            --bh-secondary: #0099ff;
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
        [data-theme="dark"] .detail-value { color: #ffffff; }
        [data-theme="dark"] .detail-label { color: #94a3b8; }
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
    @include('front.partials.rtl')
</head>
<body>
@php
    $adults = (int) ($customer->adult_count ?? 0);
    $children = (int) ($customer->child_count ?? 0);
    $guestCount = max(1, $adults + $children);
    $totalPrice = 0;
    if ($customer->type === 'activity') {
        $rec = \DB::table('activities')->where('slug', $customer->package)->orWhere('title', $customer->activity_name)->first();
        $totalPrice = (float) ($rec->price ?? 0) * $guestCount;
    } else {
        $rec = \DB::table('transfers')->where('title', $customer->package)->first();
        if ($rec) {
            if ($guestCount <= 4)       $totalPrice = (float) ($rec->price_1_4 ?? 0);
            elseif ($guestCount <= 6)   $totalPrice = (float) ($rec->price_5_6 ?? 0);
            elseif ($guestCount <= 8)   $totalPrice = (float) ($rec->price_7_8 ?? 0);
            else                        $totalPrice = (float) ($rec->price_9_14 ?? 0);
        }
    }
@endphp
    <div class="confirm-card">
        <div class="confirm-header">
            <div class="logo"><i class="fas fa-sun" style="color:var(--bh-secondary);margin-right:8px;"></i> Travel Center <strong>{{ __('Marmaris') }}</strong></div>
        </div>
        <div class="confirm-body">
            @if($customer->type === 'transfer')
                {{-- Transfer: direkt onay --}}
                <div class="confirm-icon"><i class="fas fa-check"></i></div>
                <h1>{{ __('Booking Confirmed!') }}</h1>
                <p class="subtitle">Thank you! Your transfer reservation has been received. A confirmation email has been sent to <strong>{{ $customer->email }}</strong>.</p>

                <div class="confirm-details">
                    <div class="section-label"><i class="fas fa-hashtag"></i> Booking</div>
                    <div class="detail-row"><span class="detail-label">{{ __('Booking ID') }}</span><span class="detail-value">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Name') }}</span><span class="detail-value">{{ $customer->first_name }} {{ $customer->last_name }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Transfer') }}</span><span class="detail-value">{{ $customer->package }}</span></div>

                    <div class="section-label"><i class="fas fa-plane-arrival"></i> {{ __('Arrival') }}</div>
                    <div class="detail-row"><span class="detail-label">{{ __('Date') }}</span><span class="detail-value">{{ $customer->arrival_date }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Time') }}</span><span class="detail-value">{{ $customer->arrival_time }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Flight') }}</span><span class="detail-value">{{ $customer->arrival_flight }}</span></div>

                    <div class="section-label"><i class="fas fa-plane-departure"></i> {{ __('Departure') }}</div>
                    <div class="detail-row"><span class="detail-label">{{ __('Date') }}</span><span class="detail-value">{{ $customer->departure_date }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Time') }}</span><span class="detail-value">{{ $customer->departure_time }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Flight') }}</span><span class="detail-value">{{ $customer->departure_flight }}</span></div>

                    <div class="section-label"><i class="fas fa-hotel"></i> {{ __('Details') }}</div>
                    <div class="detail-row"><span class="detail-label">{{ __('Hotel') }}</span><span class="detail-value">{{ $customer->hotel_name }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Guests') }}</span><span class="detail-value">{{ $customer->adult_count }} Adult{{ $customer->adult_count > 1 ? 's' : '' }}{{ $customer->child_count ? ', ' . $customer->child_count . ' Child' . ($customer->child_count > 1 ? 'ren' : '') : '' }}</span></div>

                    @if($totalPrice > 0)
                    <div class="detail-row" style="margin-top:10px;padding-top:12px;border-top:2px solid var(--bh-dark);">
                        <span class="detail-label" style="font-weight:700;color:var(--bh-dark);">{{ __('Total') }}</span>
                        <span class="detail-value" style="font-size:18px;font-weight:800;color:var(--bh-primary);">{{ fiyat_gbp($totalPrice) }}@if(para_cevrildi_mi())<small style="display:block;font-weight:600;font-size:12px;color:#6b7280;">{{ fiyat($totalPrice) }}</small>@endif</span>
                    </div>
                    @endif
                </div>

                @if(!empty($ayar->whatsapp))
                <a href="https://wa.me/{{ $ayar->whatsapp }}?text={{ urlencode('Hi, I have a question about my booking #TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT)) }}" target="_blank" style="display:inline-flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:12px 28px;border-radius:50px;text-decoration:none;font-weight:700;font-size:14px;margin-bottom:12px;transition:all 0.3s;">
                    <i class="fab fa-whatsapp" style="font-size:18px;"></i> Contact via WhatsApp
                </a>
                <br>
                @endif

            @else
                {{-- Aktivite: POS kapalıyken WhatsApp yönlendirme --}}
                <div class="confirm-icon" style="background:linear-gradient(135deg,#10b981,#059669);"><i class="fas fa-check"></i></div>
                <h1>{{ __('Booking Received!') }}</h1>
                <p class="subtitle">Thank you! Your booking for <strong>{{ $customer->activity_name ?: $customer->package }}</strong> has been received. Please contact us via WhatsApp to confirm and complete your reservation.</p>

                <div class="confirm-details">
                    <div class="detail-row"><span class="detail-label">{{ __('Booking ID') }}</span><span class="detail-value">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Name') }}</span><span class="detail-value">{{ $customer->first_name }} {{ $customer->last_name }}</span></div>
                    <div class="detail-row"><span class="detail-label">{{ __('Activity') }}</span><span class="detail-value">{{ $customer->activity_name ?: $customer->package }}</span></div>

                    @if($totalPrice > 0)
                    <div class="detail-row" style="margin-top:10px;padding-top:12px;border-top:2px solid var(--bh-dark);">
                        <span class="detail-label" style="font-weight:700;color:var(--bh-dark);">{{ __('Total') }}</span>
                        <span class="detail-value" style="font-size:18px;font-weight:800;color:var(--bh-primary);">{{ fiyat_gbp($totalPrice) }}@if(para_cevrildi_mi())<small style="display:block;font-weight:600;font-size:12px;color:#6b7280;">{{ fiyat($totalPrice) }}</small>@endif</span>
                    </div>
                    @endif
                </div>

                @if(!empty($ayar->whatsapp))
                <a href="https://wa.me/{{ $ayar->whatsapp }}?text={{ urlencode('Hi, I would like to complete my booking #TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT) . ' - ' . ($customer->activity_name ?: $customer->package)) }}" target="_blank" style="display:inline-flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:14px 36px;border-radius:50px;text-decoration:none;font-weight:700;font-size:15px;margin-bottom:16px;transition:all 0.3s;">
                    <i class="fab fa-whatsapp" style="font-size:20px;"></i> Contact via WhatsApp
                </a>
                <br>
                @endif
            @endif

            <a href="{{ route('anasayfa') }}" class="btn-home">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
