<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <link rel="canonical" href="{{ url('/about') }}">
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
        [data-theme="dark"] body { background: #0b1220; }
        .legal-hero { background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary)); padding: 100px 0 50px; text-align: center; }
        .legal-hero h1 { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .legal-hero p { font-size: 16px; color: rgba(255,255,255,0.7); }
        .legal-content { padding: 50px 0 80px; }
        .legal-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; padding: 40px; max-width: 850px; margin: 0 auto; }
        .legal-card h2 { font-size: 22px; font-weight: 700; color: var(--bh-dark); margin: 28px 0 12px; }
        .legal-card h2:first-child { margin-top: 0; }
        .legal-card p, .legal-card li { font-size: 15px; line-height: 1.8; color: #334155; }
        [data-theme="dark"] .legal-card { background: #1a2332; border-color: #2a3548; }
        [data-theme="dark"] .legal-card h2 { color: #ffffff; }
        [data-theme="dark"] .legal-card p,
        [data-theme="dark"] .legal-card li,
        [data-theme="dark"] .legal-card strong { color: #cbd5e1; }
        .legal-card ul { padding-left: 20px; }
        .legal-card li { margin-bottom: 6px; }
        .about-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 30px 0; }
        .about-stat { text-align: center; padding: 24px 12px; background: linear-gradient(135deg, var(--bh-primary), var(--bh-dark)); border-radius: 14px; color: #fff; }
        .about-stat .num { font-size: 32px; font-weight: 800; }
        .about-stat .lbl { font-size: 13px; opacity: 0.85; }
        @media (max-width: 576px) { .about-stats { grid-template-columns: 1fr; } }
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
                <a href="{{ route('about') }}">About</a>
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
            <h1><i class="fas fa-info-circle" style="margin-right:10px;"></i> About Us</h1>
            <p>Get to know {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</p>
        </div>
    </div>

    <div class="legal-content">
        <div class="container">
            <div class="legal-card">
                <h2>Who We Are</h2>
                <p>{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }} is a locally based travel company specialising in airport transfers and curated holiday experiences along Turkey's stunning Aegean and Mediterranean coast. From Marmaris and Fethiye to Oludeniz and beyond, we help travellers enjoy a smooth, comfortable, and memorable holiday from the moment they land.</p>

                <div class="about-stats">
                    <div class="about-stat"><div class="num">10+</div><div class="lbl">Years Experience</div></div>
                    <div class="about-stat"><div class="num">15k+</div><div class="lbl">Happy Guests</div></div>
                    <div class="about-stat"><div class="num">24/7</div><div class="lbl">Support</div></div>
                </div>

                <h2>Our Mission</h2>
                <p>To make every journey effortless and every activity unforgettable. We focus on transparent pricing in British Pounds, reliable transfers with professional drivers, and handpicked excursions that show you the real beauty of the Turkish coast.</p>

                <h2>Why Travellers Choose Us</h2>
                <ul>
                    <li><strong>Local expertise:</strong> Our team lives and works in the region we serve.</li>
                    <li><strong>English-speaking support:</strong> Friendly help before, during, and after your trip.</li>
                    <li><strong>Fair, upfront pricing:</strong> No hidden fees, all prices in £.</li>
                    <li><strong>Flexible booking:</strong> Easy online reservation with instant email confirmation.</li>
                    <li><strong>Trusted drivers & partners:</strong> Safe, clean, and on time — every time.</li>
                </ul>

                <h2>Get in Touch</h2>
                <p>Have a question or a special request? We'd love to hear from you.</p>
                <ul>
                    <li>Email: {{ $ayar->firma_email ?? '' }}</li>
                    <li>Phone: {{ $ayar->firma_telefon ?? '' }}</li>
                    <li>Address: {{ $ayar->firma_adres ?? '' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <footer id="footer" class="bh-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="bh-footer-brand">
                        @if(!empty($ayar->firma_logo))
                            <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="{{ $ayar->site_baslik ?? '' }}" style="max-height:40px;">
                        @else
                            <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                            <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
                        @endif
                    </div>
                    <p class="bh-footer-about">{{ $ayar->site_desc ?? 'We provide premium transfer services and curated holiday activities for tourists visiting Turkey\'s beautiful coast.' }}</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="bh-footer-links">
                        <li><a href="{{ route('anasayfa') }}">Home</a></li>
                        <li><a href="{{ route('anasayfa') }}#transfers">Transfers</a></li>
                        <li><a href="{{ route('anasayfa') }}#activities">Activities</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('reviews') }}">Reviews</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Transfer Routes</h5>
                    <ul class="bh-footer-links">
                        @foreach($transferRoutes as $route)
                            <li><a href="{{ route('anasayfa') }}#transfers">{{ $route->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="bh-footer-contact">
                        <li><i class="fas fa-phone-alt"></i> {{ $ayar->firma_telefon ?? '' }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $ayar->firma_email ?? '' }}</li>
                        <li><i class="fas fa-map-marker-alt"></i> {{ $ayar->firma_adres ?? '' }}</li>
                    </ul>
                    <div class="bh-footer-social">
                        @if(!empty($ayar->facebook))<a href="{{ $ayar->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($ayar->instagram))<a href="{{ $ayar->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                        @if(!empty($ayar->twitter))<a href="{{ $ayar->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>@endif
                        @if(!empty($ayar->whatsapp))<a href="https://wa.me/{{ $ayar->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bh-footer-bottom">
            <div class="container text-center">
                <p>&copy; {{ date('Y') }} {{ $ayar->copyright ?? ($ayar->site_baslik ?? 'Marmaris Travel Center') . '. All rights reserved.' }}</p>
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
