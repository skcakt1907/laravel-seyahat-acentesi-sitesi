<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <link rel="canonical" href="{{ url('/terms') }}">
    @if(!empty($ayar->favicon))
    <link rel="icon" href="{{ asset('tema/uploads/' . $ayar->favicon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tema/css/front-hero.css') }}">
    <style>
        :root {
            --bh-primary: {{ $ayar->renk1 ?? '#0066cc' }};
            --bh-secondary: {{ $ayar->renk2 ?? '#ff6b00' }};
            --bh-dark: {{ $ayar->renk3 ?? '#0b1d33' }};
        }
        body { background: #f4f7fb; }
        .legal-hero { background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary)); padding: 100px 0 50px; text-align: center; }
        .legal-hero h1 { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .legal-hero p { font-size: 16px; color: rgba(255,255,255,0.7); }
        .legal-content { padding: 50px 0 80px; }
        .legal-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; padding: 40px; max-width: 800px; margin: 0 auto; }
        .legal-card h2 { font-size: 20px; font-weight: 700; color: var(--bh-dark); margin: 28px 0 12px; }
        .legal-card h2:first-child { margin-top: 0; }
        .legal-card p, .legal-card li { font-size: 15px; line-height: 1.8; color: #334155; }
        .legal-card ul { padding-left: 20px; }
        .legal-card li { margin-bottom: 6px; }
        .legal-updated { font-size: 13px; color: #94a3b8; text-align: center; margin-top: 24px; }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
</head>
<body>
    <header class="bh-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('anasayfa') }}" class="bh-logo">
                @if(!empty($ayar->firma_logo))
                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="" style="max-height:42px;">
                @else
                    <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                @endif
                <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
            </a>
            <nav class="bh-nav" id="bhNav">
                <a href="{{ route('anasayfa') }}">Home</a>
                <a href="{{ route('anasayfa') }}#transfers">Transfers</a>
                <a href="{{ route('anasayfa') }}#activities">Excursions</a>
            </nav>
            <div class="d-flex align-items-center">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <span class="toggle-stars"></span>
                    <span class="toggle-clouds"></span>
                </button>
                <button class="bh-hamburger" id="bhHamburger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>

    <div class="legal-hero">
        <div class="container">
            <h1><i class="fas fa-file-contract" style="margin-right:10px;"></i> Terms & Conditions</h1>
            <p>Please read these terms carefully before using our services</p>
        </div>
    </div>

    <div class="legal-content">
        <div class="container">
            <div class="legal-card">
                <h2>1. Booking & Reservations</h2>
                <p>By making a booking through our website, you agree to the following terms. All bookings are subject to availability and confirmation by our team.</p>

                <h2>2. Pricing & Payment</h2>
                <ul>
                    <li>All prices are displayed in British Pounds (GBP) and include applicable taxes.</li>
                    <li>Payment is required at the time of booking through our secure payment gateway.</li>
                    <li>Prices may change without prior notice for future bookings. Confirmed bookings are not affected by price changes.</li>
                </ul>

                <h2>3. Cancellation Policy</h2>
                <ul>
                    <li><strong>Free cancellation:</strong> Up to 24 hours before the scheduled service.</li>
                    <li><strong>Late cancellation:</strong> Within 24 hours of the service — 50% charge applies.</li>
                    <li><strong>No-show:</strong> Full charge applies if you do not show up.</li>
                </ul>

                <h2>4. Transfer Services</h2>
                <ul>
                    <li>Pickup times are based on flight arrival times. Please provide accurate flight details.</li>
                    <li>We allow up to 60 minutes of free waiting time for airport pickups.</li>
                    <li>The customer is responsible for being at the designated pickup point on time.</li>
                </ul>

                <h2>5. Activity Services</h2>
                <ul>
                    <li>Activities are subject to weather conditions. In case of cancellation due to weather, a full refund or rescheduling will be offered.</li>
                    <li>Minimum age requirements may apply for certain activities.</li>
                    <li>Participants must follow all safety instructions provided by guides.</li>
                </ul>

                <h2>6. Liability</h2>
                <p>{{ $ayar->firma_adi ?? 'Marmaris Travel Center' }} acts as an intermediary for transfer and activity services. While we carefully select our service providers, we are not liable for any loss, damage, or injury caused during the service, except where caused by our direct negligence.</p>

                <h2>7. Changes to Terms</h2>
                <p>We reserve the right to update these terms at any time. Changes will be posted on this page. Continued use of our services constitutes acceptance of the updated terms.</p>

                <h2>8. Contact</h2>
                <p>For questions or concerns about these terms:</p>
                <ul>
                    <li>Email: {{ $ayar->firma_email ?? '' }}</li>
                    <li>Phone: {{ $ayar->firma_telefon ?? '' }}</li>
                    <li>Address: {{ $ayar->firma_adres ?? '' }}</li>
                </ul>

                <p class="legal-updated">Last updated: {{ date('F Y') }}</p>
            </div>
        </div>
    </div>

    <footer class="bh-footer">
        <div class="bh-footer-bottom">
            <div class="container text-center">
                <p>&copy; {{ date('Y') }} {{ $ayar->copyright ?? ($ayar->site_baslik ?? 'Marmaris Travel Center') }}</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('tema/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tema/js/bootstrap.min.js') }}"></script>
    <script>
    (function() {
        var h = document.getElementById('bhHamburger'), n = document.getElementById('bhNav');
        if (h && n) h.addEventListener('click', function() { h.classList.toggle('active'); n.classList.toggle('open'); });
        var hdr = document.querySelector('.bh-header');
        window.addEventListener('scroll', function() { hdr.classList.toggle('scrolled', window.scrollY > 80); });
    })();
    </script>
    <div class="theme-transition-overlay" id="themeOverlay"></div>
    <script>
    (function(){
        var toggle = document.getElementById('themeToggle');
        var overlay = document.getElementById('themeOverlay');
        if (toggle) {
            toggle.addEventListener('click', function() {
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                overlay.classList.remove('flash');
                void overlay.offsetWidth;
                overlay.classList.add('flash');
                if (isDark) {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                }
                setTimeout(function() { overlay.classList.remove('flash'); }, 500);
            });
        }
    })();
    </script>
</body>
</html>
