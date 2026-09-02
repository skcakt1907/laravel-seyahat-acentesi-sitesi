<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }} — Transfer & Activity Booking</title>
    <meta name="description" content="{{ $ayar->site_desc ?? 'Book premium airport transfers and exciting holiday activities in Marmaris, Fethiye, Oludeniz and more. Safe, affordable and reliable service.' }}">
    <meta name="keywords" content="Marmaris transfer, Dalaman airport transfer, Marmaris activities, holiday activities Turkey, Fethiye transfer, Oludeniz tours">
    <meta property="og:title" content="{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }} — Transfer & Activity Booking">
    <meta property="og:description" content="{{ $ayar->site_desc ?? 'Book premium airport transfers and exciting holiday activities in Marmaris, Fethiye, Oludeniz and more.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
    @if(!empty($ayar->favicon))
    <link rel="icon" href="{{ asset('tema/uploads/' . $ayar->favicon) }}">
    @endif
    @php
    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'TravelAgency',
        'name' => $ayar->site_baslik ?? 'Marmaris Travel Center',
        'url' => url('/'),
        'telephone' => $ayar->firma_telefon ?? '',
        'email' => $ayar->firma_email ?? '',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $ayar->firma_adres ?? '',
            'addressLocality' => 'Marmaris',
            'addressCountry' => 'TR',
        ],
        'areaServed' => ['Marmaris', 'Fethiye', 'Oludeniz', 'Dalaman'],
        'description' => $ayar->site_desc ?? 'Premium airport transfers and holiday activities in Marmaris, Turkey.',
    ];
    if ($reviews->count() > 0) {
        $schemaData['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => number_format($reviews->avg('rating'), 1),
            'reviewCount' => (string) $reviews->count(),
            'bestRating' => '5',
        ];
    }
    @endphp
    @include('front.partials.currency-js')
    <script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @include('front.partials.gtag')
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tema/css/front-hero.css') }}?v={{ filemtime(public_path('tema/css/front-hero.css')) }}">
    <style>
        :root {
            --bh-primary: {{ $ayar->renk1 ?? '#0066cc' }};
            --bh-secondary: {{ $ayar->renk2 ?? '#0099ff' }};
            --bh-dark: {{ $ayar->renk3 ?? '#0b1d33' }};
        }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
    @include('front.partials.rtl')
</head>
<body>

    {{-- ========== TOP BAR ========== --}}
    <div class="bh-topbar">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="bh-topbar-left">
                <span><i class="fas fa-phone-alt"></i> {{ $ayar->firma_telefon ?? '' }}</span>
                <span class="d-none d-md-inline"><i class="fas fa-envelope"></i> {{ $ayar->firma_email ?? '' }}</span>
            </div>
            <div class="bh-topbar-right">
                @if(!empty($ayar->facebook))<a href="{{ $ayar->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                @if(!empty($ayar->instagram))<a href="{{ $ayar->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                @if(!empty($ayar->twitter))<a href="{{ $ayar->twitter }}" target="_blank"><i class="fab fa-tiktok"></i></a>@endif
                @if(!empty($ayar->whatsapp))<a href="https://wa.me/{{ $ayar->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>@endif
            </div>
        </div>
    </div>

    {{-- ========== HEADER / NAVBAR ========== --}}
    <header class="bh-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('anasayfa') }}" class="bh-logo">
                @if(!empty($ayar->firma_logo))
                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="{{ $ayar->site_baslik ?? '' }}" style="max-height:42px;">
                @else
                    <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                @endif
                <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
            </a>
            <nav class="bh-nav" id="bhNav">
                <a href="#hero">{{ __('Home') }}</a>
                @foreach($sectionOrder as $navSection)
                    @if($navSection === 'transfers')
                        <a href="#transfers">{{ __('Transfers') }}</a>
                    @elseif($navSection === 'activities')
                        <a href="#activities">{{ __('Excursions') }}</a>
                    @elseif($navSection === 'about')
                        <a href="{{ route('about') }}">{{ __('About') }}</a>
                    @elseif($navSection === 'why-us')
                        <a href="#why-us">{{ __('Why Us') }}</a>
                    @elseif($navSection === 'testimonials')
                        <a href="{{ route('reviews') }}">{{ __('Reviews') }}</a>
                    @elseif($navSection === 'contact')
                        <a href="#contact">{{ __('Contact') }}</a>
                    @endif
                @endforeach
            </nav>
            <div class="d-flex align-items-center">
                @include('front.partials.lang-switch')
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <span class="toggle-stars"></span>
                    <span class="toggle-clouds"></span>
                </button>
                <button class="bh-hamburger" id="bhHamburger" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    {{-- ========== HERO SLIDER ========== --}}
    <section id="hero" class="bh-hero">
        <div id="heroSlider" class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000" data-bs-ride="carousel" data-bs-interval="5000">
            <ol class="carousel-indicators">
                @foreach($slides as $slide)
                    <li data-target="#heroSlider" data-bs-target="#heroSlider" data-slide-to="{{ $loop->index }}" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                @endforeach
            </ol>
            <div class="carousel-inner">
                @forelse($slides as $slide)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        @if(($slide->media_type ?? 'image') === 'video' && !empty($slide->video))
                            <video class="bh-hero-media" autoplay muted loop playsinline>
                                <source src="{{ asset('tema/uploads/slider/videos/' . rawurlencode($slide->video)) }}" type="video/mp4">
                            </video>
                        @else
                            @php $slideImg = asset('tema/uploads/slider/' . rawurlencode($slide->resim ?? '')); @endphp
                            <div class="bh-hero-bg-blur" style="background-image:url('{{ $slideImg }}');"></div>
                            <img class="bh-hero-media" src="{{ $slideImg }}" alt="{{ ic($slide, 'adi') ?? 'Slide' }}">
                        @endif
                        <div class="bh-hero-overlay"></div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="bh-hero-media bh-hero-fallback"></div>
                        <div class="bh-hero-overlay"></div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ========== DYNAMIC SECTIONS (order from admin) ========== --}}
    @foreach($sectionOrder as $section)
        @includeIf('front.sections.' . $section)
    @endforeach

    {{-- ========== FOOTER ========== --}}
    <footer id="footer" class="bh-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 mb-4">
                    <div class="bh-footer-brand" style="display:flex;align-items:center;gap:12px;">
                        @if(!empty($ayar->firma_logo))
                            <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="{{ $ayar->site_baslik ?? '' }}" style="max-height:44px;">
                        @else
                            <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                        @endif
                        <span class="bh-logo-text" style="font-size:20px;font-weight:700;">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('Quick Links') }}</h5>
                    <ul class="bh-footer-links">
                        <li><a href="#transfers">{{ __('Transfers') }}</a></li>
                        <li><a href="#activities">{{ __('Excursions') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('About') }}</a></li>
                        <li><a href="{{ route('reviews') }}">{{ __('Reviews') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>{{ __('Contact') }}</h5>
                    <ul class="bh-footer-contact">
                        @if(!empty($ayar->firma_telefon))<li><i class="fas fa-phone-alt"></i> {{ $ayar->firma_telefon }}</li>@endif
                        @if(!empty($ayar->firma_email))<li><i class="fas fa-envelope"></i> {{ $ayar->firma_email }}</li>@endif
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

    {{-- ========== WHATSAPP FLOAT ========== --}}
    <a href="https://wa.me/{{ $ayar->whatsapp ?? '' }}" class="bh-wa-float" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- ========== COOKIE CONSENT ========== --}}
    <div class="cookie-banner" id="cookieBanner" style="display:none;">
        <div class="cookie-inner">
            <div class="cookie-text">
                <i class="fas fa-cookie-bite" style="color:var(--bh-secondary);font-size:20px;margin-right:10px;"></i>
                We use essential cookies to ensure our website works properly. By continuing to browse, you agree to our
                <a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>.
            </div>
            <button class="cookie-accept" onclick="acceptCookies()">{{ __('Accept') }}</button>
        </div>
    </div>
    <style>
        .cookie-banner {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 9997;
            background: var(--bh-dark); padding: 16px 0;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            animation: cookieSlideUp 0.4s ease;
        }
        @keyframes cookieSlideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .cookie-inner {
            max-width: 1140px; margin: 0 auto; padding: 0 20px;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
        }
        .cookie-text { font-size: 14px; color: rgba(255,255,255,0.8); line-height: 1.6; }
        .cookie-text a { color: var(--bh-secondary); text-decoration: underline; }
        .cookie-accept {
            background: var(--bh-secondary); color: #fff; border: none; padding: 10px 28px;
            border-radius: 50px; font-size: 14px; font-weight: 700; cursor: pointer;
            font-family: 'Poppins', sans-serif; white-space: nowrap; transition: all 0.2s;
        }
        .cookie-accept:hover { background: #0066cc; transform: translateY(-1px); }
        @media (max-width: 768px) { .cookie-inner { flex-direction: column; text-align: center; } }
    </style>
    <script>
    (function() {
        if (!localStorage.getItem('cookie_consent')) {
            document.getElementById('cookieBanner').style.display = 'block';
        }
    })();
    function acceptCookies() {
        localStorage.setItem('cookie_consent', '1');
        document.getElementById('cookieBanner').style.display = 'none';
    }
    </script>

    <div class="theme-transition-overlay" id="themeOverlay"></div>
    <script src="{{ asset('tema/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tema/js/bootstrap.min.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Force hero carousel auto-rotation (works on BS4 + BS5)
        var slider = document.getElementById('heroSlider');
        if (slider) {
            try {
                if (window.jQuery && jQuery.fn.carousel) {
                    jQuery(slider).carousel({ interval: 5000, ride: 'carousel', pause: false });
                } else if (window.bootstrap && bootstrap.Carousel) {
                    new bootstrap.Carousel(slider, { interval: 5000, ride: 'carousel', pause: false });
                }
            } catch (e) { console.warn('Carousel init failed', e); }

            // Per-image fit: switch to 'cover' when image aspect ratio is close to container,
            // otherwise leave as 'contain' so the entire image remains visible.
            var TOLERANCE = 0.18; // ~18% deviation from container aspect → still cover
            function applyFit(img) {
                if (!img || !img.naturalWidth) return;
                var containerEl = slider.querySelector('.carousel-inner') || slider;
                var cw = containerEl.clientWidth || window.innerWidth;
                var ch = containerEl.clientHeight || window.innerHeight * 0.78;
                var containerAR = cw / ch;
                var imgAR = img.naturalWidth / img.naturalHeight;
                var diff = Math.abs(imgAR - containerAR) / containerAR;
                img.classList.toggle('fit-cover', diff <= TOLERANCE);
            }
            slider.querySelectorAll('img.bh-hero-media').forEach(function(img) {
                if (img.complete) applyFit(img);
                else img.addEventListener('load', function() { applyFit(img); });
            });
            window.addEventListener('resize', function() {
                slider.querySelectorAll('img.bh-hero-media').forEach(applyFit);
            });
        }

        // Mobile hamburger menu
        var hamburger = document.getElementById('bhHamburger');
        var nav = document.getElementById('bhNav');
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            nav.classList.toggle('open');
        });

        // Close mobile menu on link click
        nav.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                nav.classList.remove('open');
            });
        });

        // Transfer card click -> open form
        var formPanel = document.getElementById('transferFormPanel');
        var routeInput = document.getElementById('transferRouteInput');
        var routeTitle = document.getElementById('selectedRouteTitle');
        var closeBtn = document.getElementById('closeTransferForm');
        var cards = document.querySelectorAll('.bh-transfer-card');

        // Tier-based pricing helper
        var currentTiers = null;
        function tierFor(total) {
            if (!currentTiers) return 0;
            if (total <= 4)  return parseFloat(currentTiers['1-4'] || 0);
            if (total <= 6)  return parseFloat(currentTiers['5-6'] || 0);
            if (total <= 8)  return parseFloat(currentTiers['7-8'] || 0);
            if (total <= 14) return parseFloat(currentTiers['9-14'] || 0);
            return parseFloat(currentTiers['9-14'] || 0);
        }
        function recalcTransferPrice() {
            var priceBar = document.getElementById('transferPriceBar');
            var priceDisplay = document.getElementById('transferPriceDisplay');
            var paxLabel = document.getElementById('transferPaxLabel');
            if (!priceBar || !currentTiers) return;
            var adults = parseInt(document.querySelector('input[name="adult_count"]').value || '1', 10);
            var children = parseInt(document.querySelector('input[name="child_count"]').value || '0', 10);
            var total = adults + children;
            var price = tierFor(total);
            if (price > 0) {
                priceBar.style.display = 'flex';
                priceDisplay.textContent = window.__fiyatGoster(price);
                if (paxLabel) paxLabel.textContent = '(' + total + ' pax)';
            } else {
                priceBar.style.display = 'none';
            }
        }

        if (formPanel && cards.length > 0) {
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    cards.forEach(function(c) { c.classList.remove('active'); });
                    this.classList.add('active');
                    var route = this.dataset.route || '';
                    currentTiers = {
                        '1-4':  this.dataset.price14 || this.getAttribute('data-price-1-4') || 0,
                        '5-6':  this.dataset.price56 || this.getAttribute('data-price-5-6') || 0,
                        '7-8':  this.dataset.price78 || this.getAttribute('data-price-7-8') || 0,
                        '9-14': this.dataset.price914 || this.getAttribute('data-price-9-14') || 0,
                    };
                    if (routeInput) routeInput.value = route;
                    if (routeTitle) routeTitle.textContent = route;
                    recalcTransferPrice();
                    formPanel.classList.add('open');
                    setTimeout(function() {
                        formPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                });
            });

            // Recalculate when adult/child count changes
            ['adult_count', 'child_count'].forEach(function(name) {
                var el = document.querySelector('input[name="' + name + '"]');
                if (el) el.addEventListener('input', recalcTransferPrice);
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    formPanel.classList.remove('open');
                    cards.forEach(function(c) { c.classList.remove('active'); });
                });
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Header scroll effect
        var header = document.querySelector('.bh-header');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 80) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Testimonials — clone cards for infinite seamless loop
        var track = document.getElementById('testimonialTrack');
        if (track) {
            var trackCards = track.innerHTML;
            track.innerHTML = trackCards + trackCards;
        }

        // Theme toggle
        var toggle = document.getElementById('themeToggle');
        var overlay = document.getElementById('themeOverlay');
        var saved = localStorage.getItem('theme');
        if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
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
    });
    </script>
    @include('partials.intl-tel')
</body>
</html>
