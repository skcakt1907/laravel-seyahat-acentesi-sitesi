<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <link rel="canonical" href="{{ url('/privacy') }}">
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
                <a href="{{ route('anasayfa') }}#activities">Activities</a>
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
            <h1><i class="fas fa-shield-alt" style="margin-right:10px;"></i> Privacy Policy</h1>
            <p>How we collect, use and protect your data</p>
        </div>
    </div>

    <div class="legal-content">
        <div class="container">
            <div class="legal-card">
                <h2>1. Information We Collect</h2>
                <p>When you make a booking or contact us, we collect the following information:</p>
                <ul>
                    <li>Full name, email address, and phone number</li>
                    <li>Transfer route or activity preferences</li>
                    <li>Payment information (processed securely through our payment provider)</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We use your personal data to:</p>
                <ul>
                    <li>Process and confirm your bookings</li>
                    <li>Communicate with you about your reservation</li>
                    <li>Send booking confirmations via email</li>
                    <li>Improve our services and customer experience</li>
                </ul>

                <h2>3. Data Protection</h2>
                <p>We implement appropriate security measures to protect your personal information. Payment data is processed through secure, PCI-compliant payment gateways and is never stored on our servers.</p>

                <h2>4. Third-Party Sharing</h2>
                <p>We do not sell or share your personal information with third parties, except as necessary to:</p>
                <ul>
                    <li>Process payments through our payment provider</li>
                    <li>Provide the booked transfer or activity service</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h2>5. Cookies</h2>
                <p>Our website uses essential cookies to ensure proper functionality, including session management and security tokens. We do not use tracking or advertising cookies.</p>

                <h2>6. Your Rights</h2>
                <p>You have the right to access, correct, or delete your personal data. To exercise these rights, please contact us at <a href="mailto:{{ $ayar->firma_email ?? '' }}" style="color:var(--bh-primary);">{{ $ayar->firma_email ?? '' }}</a>.</p>

                <h2>7. Contact</h2>
                <p>If you have any questions about this privacy policy, please contact us:</p>
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
