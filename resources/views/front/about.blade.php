<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>About Us — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <link rel="canonical" href="{{ url('/about') }}">
    @if(!empty($ayar->favicon))
    <link rel="icon" href="{{ asset('tema/uploads/' . $ayar->favicon) }}">
    @endif
    @include('front.partials.gtag')
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tema/css/front-hero.css') }}">
    <style>
        :root {
            --bh-primary: {{ $ayar->renk1 ?? '#0066cc' }};
            --bh-secondary: {{ $ayar->renk2 ?? '#0099ff' }};
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
    @include('front.partials.rtl')
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
                <a href="{{ route('anasayfa') }}">{{ __('Home') }}</a>
                <a href="{{ route('anasayfa') }}#transfers">{{ __('Transfers') }}</a>
                <a href="{{ route('anasayfa') }}#activities">{{ __('Excursions') }}</a>
                <a href="{{ route('about') }}">{{ __('About') }}</a>
            </nav>
            <div class="d-flex align-items-center">
                @include('front.partials.lang-switch')
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
            <h1><i class="fas fa-info-circle" style="margin-right:10px;"></i> {{ __('About Us') }}</h1>
            <p>Get to know {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</p>
        </div>
    </div>

    @php
        $stats = $ayar && !empty($ayar->about_stats) ? json_decode($ayar->about_stats, true) : [];
        $features = $ayar && !empty($ayar->about_features) ? json_decode($ayar->about_features, true) : [];
        $aboutTitle = ic($ayar, 'about_title') ?: 'Who We Are';
        $aboutText = ic($ayar, 'about_text') ?: ($ayar->site_baslik ?? 'Marmaris Travel Center') . ' is a locally based travel company specialising in airport transfers and curated holiday experiences along Turkey\'s stunning Aegean and Mediterranean coast. From Marmaris and Fethiye to Oludeniz and beyond, we help travellers enjoy a smooth, comfortable, and memorable holiday from the moment they land.';
        $aboutMission = ic($ayar, 'about_mission') ?: 'To make every journey effortless and every activity unforgettable. We focus on transparent pricing in British Pounds, reliable transfers with professional drivers, and handpicked excursions that show you the real beauty of the Turkish coast.';

        if (empty($stats) || empty($stats[0]['num'])) {
            $stats = [
                ['num' => '10+',  'label' => __('Years Experience')],
                ['num' => '15k+', 'label' => __('Happy Guests')],
                ['num' => '24/7', 'label' => __('Support')],
            ];
        }

        // Yoneticinin girdigi maddelerin dile gore cevirisi (JSON ya da satir satir metin olabilir)
        $__ozellikCeviri = ic($ayar, 'about_features');
        if ($__ozellikCeviri && $__ozellikCeviri !== ($ayar->about_features ?? null)) {
            $__cozulen = json_decode($__ozellikCeviri, true);
            if (! is_array($__cozulen)) {
                $__cozulen = array_values(array_filter(array_map('trim', preg_split('/
?
/', $__ozellikCeviri))));
            }
            if ($__cozulen) {
                $features = $__cozulen;
            }
        }

        if (empty($features)) {
            $features = [
                __('Local expertise: Our team lives and works in the region we serve.'),
                __('English-speaking support: Friendly help before, during, and after your trip.'),
                __('Fair, upfront pricing: No hidden fees, all prices in :para.', ['para' => \App\Helpers\SiteCurrency::BASE]),
                __('Flexible booking: Easy online reservation with instant email confirmation.'),
                __('Trusted drivers & partners: Safe, clean, and on time — every time.'),
            ];
        }
    @endphp

    <div class="legal-content">
        <div class="container">
            <div class="legal-card">
                @if(!empty($ayar->about_image))
                    <div style="text-align:center;margin-bottom:28px;">
                        <img src="{{ asset('tema/uploads/' . $ayar->about_image) }}" alt="{{ $aboutTitle }}" style="max-width:100%;border-radius:14px;">
                    </div>
                @endif

                <h2>{{ $aboutTitle }}</h2>
                <p>{{ $aboutText }}</p>

                <div class="about-stats">
                    @foreach($stats as $stat)
                        @if(!empty($stat['num']))
                        <div class="about-stat"><div class="num">{{ $stat['num'] }}</div><div class="lbl">{{ $stat['label'] ?? '' }}</div></div>
                        @endif
                    @endforeach
                </div>

                <h2>{{ __('Our Mission') }}</h2>
                <p>{{ $aboutMission }}</p>

                <h2>{{ __('Why Travellers Choose Us') }}</h2>
                <ul>
                    @foreach($features as $feature)
                        @if(!empty($feature))
                        @php
                            $parts = explode(':', $feature, 2);
                        @endphp
                        <li>
                            @if(count($parts) === 2)
                                <strong>{{ trim($parts[0]) }}:</strong>{{ trim($parts[1]) }}
                            @else
                                {{ $feature }}
                            @endif
                        </li>
                        @endif
                    @endforeach
                </ul>

                <h2>{{ __('Get in Touch') }}</h2>
                <p>{{ __('Have a question or a special request? We\'d love to hear from you.') }}</p>
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
                    <h5>{{ __('Quick Links') }}</h5>
                    <ul class="bh-footer-links">
                        <li><a href="{{ route('anasayfa') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('anasayfa') }}#transfers">{{ __('Transfers') }}</a></li>
                        <li><a href="{{ route('anasayfa') }}#activities">{{ __('Excursions') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('reviews') }}">{{ __('Reviews') }}</a></li>
                        <li><a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="{{ route('terms') }}">{{ __('Terms & Conditions') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('Transfer Routes') }}</h5>
                    <ul class="bh-footer-links">
                        @foreach($transferRoutes as $route)
                            <li><a href="{{ route('anasayfa') }}#transfers">{{ ic($route, 'title') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('Contact Us') }}</h5>
                    <ul class="bh-footer-contact">
                        <li><i class="fas fa-phone-alt"></i> {{ $ayar->firma_telefon ?? '' }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $ayar->firma_email ?? '' }}</li>
                        <li><i class="fas fa-map-marker-alt"></i> {{ $ayar->firma_adres ?? '' }}</li>
                    </ul>
                    <div class="bh-footer-social">
                        @if(!empty($ayar->facebook))<a href="{{ $ayar->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($ayar->instagram))<a href="{{ $ayar->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                        @if(!empty($ayar->twitter))<a href="{{ $ayar->twitter }}" target="_blank"><i class="fab fa-tiktok"></i></a>@endif
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
