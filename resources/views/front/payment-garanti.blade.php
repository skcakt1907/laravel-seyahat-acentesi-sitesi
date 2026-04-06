<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment — Travel Center Marmaris</title>
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
            background: linear-gradient(135deg, #f4f7fb 0%, #e8f2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .payment-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            max-width: 560px;
            width: 100%;
            overflow: hidden;
        }
        .payment-header {
            background: var(--bh-dark);
            padding: 24px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-header .logo {
            font-size: 18px;
            color: #fff;
            font-weight: 400;
        }
        .payment-header .logo strong {
            font-weight: 800;
            color: var(--bh-secondary);
        }
        .payment-header .secure-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #10b981;
            font-size: 13px;
            font-weight: 600;
        }
        .payment-body {
            padding: 36px;
        }
        .order-summary {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .order-summary h3 {
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }
        .order-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .order-row:last-child { border-bottom: none; }
        .order-label { color: #64748b; }
        .order-value { color: var(--bh-dark); font-weight: 600; }
        .payment-info {
            text-align: center;
            padding: 30px 0;
        }
        .payment-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #fff;
            margin-bottom: 20px;
        }
        .payment-icon.active { background: linear-gradient(135deg, var(--bh-primary), #004999); }
        .payment-icon.inactive { background: linear-gradient(135deg, #94a3b8, #64748b); }
        .payment-info h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--bh-dark);
            margin-bottom: 8px;
        }
        .payment-info p {
            color: #64748b;
            font-size: 14px;
            max-width: 380px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }
        .btn-back {
            display: inline-block;
            margin-top: 16px;
            color: #64748b;
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-back:hover { color: var(--bh-dark); }
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #25d366;
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .whatsapp-btn:hover {
            background: #1da851;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,211,102,0.35);
            color: #fff;
            text-decoration: none;
        }

        /* Credit Card Form */
        .cc-form { text-align: left; }
        .cc-form .form-group {
            margin-bottom: 20px;
        }
        .cc-form label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--bh-dark);
            margin-bottom: 6px;
        }
        .cc-form .form-control {
            width: 100%;
            height: 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 16px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: var(--bh-dark);
            transition: all 0.2s;
            background: #fff;
            letter-spacing: 0.5px;
        }
        .cc-form .form-control:focus {
            border-color: var(--bh-primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
        }
        .cc-form .form-control::placeholder {
            color: #94a3b8;
        }
        .cc-form .row-2 {
            display: flex;
            gap: 16px;
        }
        .cc-form .row-2 .form-group {
            flex: 1;
        }
        .cc-visual {
            background: linear-gradient(135deg, var(--bh-dark) 0%, #1a3a5c 100%);
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 28px;
            color: #fff;
            position: relative;
            min-height: 180px;
            overflow: hidden;
        }
        .cc-visual::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .cc-visual::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -20px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .cc-visual .chip {
            width: 44px;
            height: 32px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .cc-visual .card-number {
            font-size: 20px;
            letter-spacing: 3px;
            font-weight: 600;
            margin-bottom: 20px;
            font-family: 'Courier New', monospace;
        }
        .cc-visual .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .cc-visual .card-holder-label,
        .cc-visual .card-expiry-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 4px;
        }
        .cc-visual .card-holder-value,
        .cc-visual .card-expiry-value {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .cc-brand {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 28px;
            opacity: 0.9;
        }
        .btn-pay {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: var(--bh-secondary);
            color: #fff;
            padding: 16px 44px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }
        .btn-pay:hover {
            background: #e05500;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,107,0,0.35);
            color: #fff;
        }
        .btn-pay:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .secure-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
            font-size: 12px;
            color: #10b981;
            font-weight: 500;
        }
        .alert {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14px;
            margin-bottom: 20px;
            border: none;
        }
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
        }
        .alert-info {
            background: #eff6ff;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="payment-header">
            <div class="logo">
                @if(!empty($ayar->firma_logo))
                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="" style="max-height:36px;">
                @else
                    <i class="fas fa-sun" style="color:var(--bh-secondary);margin-right:8px;"></i> Travel Center <strong>Marmaris</strong>
                @endif
            </div>
            <div class="secure-badge"><i class="fas fa-lock"></i> Secure Payment</div>
        </div>
        <div class="payment-body">

            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
            @endif

            <div class="order-summary">
                <h3>Order Summary</h3>
                <div class="order-row">
                    <span class="order-label">Customer</span>
                    <span class="order-value">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Email</span>
                    <span class="order-value">{{ $customer->email }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">{{ $customer->type === 'transfer' ? 'Transfer Route' : 'Activity' }}</span>
                    <span class="order-value">{{ $customer->activity_name ?: $customer->package }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Order ID</span>
                    <span class="order-value">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                @if($price > 0)
                <div class="order-row">
                    <span class="order-label">Amount</span>
                    <span class="order-value" style="color: var(--bh-secondary); font-size: 16px;">&pound;{{ number_format($price, 0) }}</span>
                </div>
                @endif
            </div>

            @if($odemeAyarlari && !empty($odemeAyarlari['aktif']) && !empty($odemeAyarlari['provider']))
                {{-- Credit Card Payment Form --}}
                <div class="cc-visual">
                    <div class="cc-brand" id="ccBrandIcon"><i class="far fa-credit-card"></i></div>
                    <div class="chip"></div>
                    <div class="card-number" id="ccPreviewNumber">**** **** **** ****</div>
                    <div class="card-bottom">
                        <div>
                            <div class="card-holder-label">Card Holder</div>
                            <div class="card-holder-value" id="ccPreviewName">YOUR NAME</div>
                        </div>
                        <div>
                            <div class="card-expiry-label">Expires</div>
                            <div class="card-expiry-value" id="ccPreviewExpiry">MM/YY</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('payment.process', $customer->id) }}" method="POST" class="cc-form" id="paymentForm">
                    @csrf

                    <div class="form-group">
                        <label for="card_holder"><i class="fas fa-user" style="margin-right:4px;color:var(--bh-primary);"></i> Card Holder Name</label>
                        <input type="text" class="form-control" id="card_holder" name="card_holder"
                               placeholder="Name on card" required autocomplete="cc-name">
                    </div>

                    <div class="form-group">
                        <label for="card_number"><i class="fas fa-credit-card" style="margin-right:4px;color:var(--bh-primary);"></i> Card Number</label>
                        <input type="text" class="form-control" id="card_number" name="card_number"
                               placeholder="1234 5678 9012 3456" required maxlength="19"
                               inputmode="numeric" autocomplete="cc-number">
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label for="expiry_month"><i class="fas fa-calendar" style="margin-right:4px;color:var(--bh-primary);"></i> Expiry Date</label>
                            <div style="display:flex;gap:8px;">
                                <select class="form-control" id="expiry_month" name="expiry_month" required style="cursor:pointer;">
                                    <option value="">Month</option>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                <select class="form-control" id="expiry_year" name="expiry_year" required style="cursor:pointer;">
                                    <option value="">Year</option>
                                    @for($y = date('Y'); $y <= date('Y') + 10; $y++)
                                        <option value="{{ substr($y, -2) }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cvv"><i class="fas fa-lock" style="margin-right:4px;color:var(--bh-primary);"></i> CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv"
                                   placeholder="123" required maxlength="4"
                                   inputmode="numeric" autocomplete="cc-csc">
                        </div>
                    </div>

                    <button type="submit" class="btn-pay" id="btnPay">
                        <i class="fas fa-lock"></i>
                        Pay {{ $price > 0 ? '£' . number_format($price, 0) : '' }} — Secure Payment
                    </button>
                </form>

                <div class="secure-note">
                    <i class="fas fa-shield-alt"></i> Your payment is protected with 3D Secure encryption
                </div>

                <div style="text-align:center;">
                    <a href="{{ route('anasayfa') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Home</a>
                </div>
            @else
                {{-- No payment gateway configured - show confirmation + WhatsApp --}}
                <div class="payment-info">
                    <div class="payment-icon active"><i class="fas fa-check"></i></div>
                    <h2>Reservation Received!</h2>
                    <p>Your booking has been confirmed. Our team will contact you shortly to arrange payment details.</p>
                    @if(!empty($ayar->whatsapp))
                        <a href="https://wa.me/{{ $ayar->whatsapp }}?text={{ urlencode('Hi, I have a booking #TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT) . ' - ' . ($customer->activity_name ?: $customer->package)) }}" target="_blank" class="whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> Contact via WhatsApp
                        </a>
                        <br>
                    @endif
                    <a href="{{ route('anasayfa') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Home</a>
                </div>
            @endif
        </div>
    </div>

    <script>
    (function() {
        // Card number formatting & brand detection
        var cardInput = document.getElementById('card_number');
        var brandIcon = document.getElementById('ccBrandIcon');
        var previewNumber = document.getElementById('ccPreviewNumber');
        var previewName = document.getElementById('ccPreviewName');
        var previewExpiry = document.getElementById('ccPreviewExpiry');

        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                var val = this.value.replace(/\D/g, '');
                if (val.length > 16) val = val.substring(0, 16);
                // Format with spaces
                var formatted = val.replace(/(\d{4})(?=\d)/g, '$1 ');
                this.value = formatted;

                // Preview
                var display = val;
                while (display.length < 16) display += '*';
                previewNumber.textContent = display.replace(/(.{4})/g, '$1 ').trim();

                // Brand detection
                if (/^4/.test(val)) {
                    brandIcon.innerHTML = '<i class="fab fa-cc-visa"></i>';
                } else if (/^5[1-5]/.test(val) || /^2[2-7]/.test(val)) {
                    brandIcon.innerHTML = '<i class="fab fa-cc-mastercard"></i>';
                } else if (/^3[47]/.test(val)) {
                    brandIcon.innerHTML = '<i class="fab fa-cc-amex"></i>';
                } else if (/^9/.test(val)) {
                    brandIcon.innerHTML = '<span style="font-size:16px;font-weight:700;">TROY</span>';
                } else {
                    brandIcon.innerHTML = '<i class="far fa-credit-card"></i>';
                }
            });
        }

        // Card holder preview
        var holderInput = document.getElementById('card_holder');
        if (holderInput) {
            holderInput.addEventListener('input', function() {
                previewName.textContent = this.value.toUpperCase() || 'YOUR NAME';
            });
        }

        // Expiry preview
        var monthSelect = document.getElementById('expiry_month');
        var yearSelect = document.getElementById('expiry_year');
        function updateExpiry() {
            var m = monthSelect ? monthSelect.value : '';
            var y = yearSelect ? yearSelect.value : '';
            previewExpiry.textContent = (m || 'MM') + '/' + (y || 'YY');
        }
        if (monthSelect) monthSelect.addEventListener('change', updateExpiry);
        if (yearSelect) yearSelect.addEventListener('change', updateExpiry);

        // CVV - only numbers
        var cvvInput = document.getElementById('cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        }

        // Prevent double submit
        var form = document.getElementById('paymentForm');
        if (form) {
            form.addEventListener('submit', function() {
                var btn = document.getElementById('btnPay');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            });
        }
    })();
    </script>
</body>
</html>
