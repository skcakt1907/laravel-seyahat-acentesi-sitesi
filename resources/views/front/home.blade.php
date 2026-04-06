<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }} — Transfer & Activity Booking</title>
    <meta name="description" content="{{ $ayar->site_desc ?? 'Book premium airport transfers and exciting holiday activities in Marmaris, Fethiye, Oludeniz and more. Safe, affordable and reliable service.' }}">
    <meta name="keywords" content="Marmaris transfer, Dalaman airport transfer, Marmaris activities, holiday activities Turkey, Fethiye transfer, Oludeniz tours">
    <meta property="og:title" content="{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }} — Transfer & Activity Booking">
    <meta property="og:description" content="{{ $ayar->site_desc ?? 'Book premium airport transfers and exciting holiday activities in Marmaris, Fethiye, Oludeniz and more.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    @if(!empty($ayar->favicon))
    <link rel="icon" href="{{ asset('tema/uploads/' . $ayar->favicon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('tema/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tema/css/front-hero.css') }}">
    <style>
        :root {
            --bh-primary: {{ $ayar->renk1 ?? '#0066cc' }};
            --bh-secondary: {{ $ayar->renk2 ?? '#ff6b00' }};
            --bh-dark: {{ $ayar->renk3 ?? '#0b1d33' }};
        }
    </style>
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
                <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</span>
            </a>
            <nav class="bh-nav" id="bhNav">
                <a href="#hero">Home</a>
                @foreach($sectionOrder as $navSection)
                    @if($navSection === 'transfers')
                        <a href="#transfers">Transfers</a>
                    @elseif($navSection === 'activities')
                        <a href="#activities">Activities</a>
                    @elseif($navSection === 'why-us')
                        <a href="#why-us">Why Us</a>
                    @elseif($navSection === 'testimonials')
                        <a href="{{ route('reviews') }}">Reviews</a>
                    @elseif($navSection === 'contact')
                        <a href="#contact">Contact</a>
                    @endif
                @endforeach
            </nav>
            <button class="bh-hamburger" id="bhHamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    {{-- ========== HERO SLIDER ========== --}}
    <section id="hero" class="bh-hero">
        <div id="heroSlider" class="carousel slide carousel-fade" data-ride="carousel" data-interval="6000">
            <ol class="carousel-indicators">
                @foreach($slides as $slide)
                    <li data-target="#heroSlider" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
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
                            <img class="bh-hero-media" src="{{ asset('tema/uploads/slider/' . rawurlencode($slide->resim ?? '')) }}" alt="{{ $slide->adi ?? 'Slide' }}">
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
            <div class="bh-hero-content">
                <h1>Discover Turkey's<br><span>Hidden Paradise</span></h1>
                <p>Premium transfers & unforgettable activities in Fethiye, Oludeniz, Marmaris & more</p>
                <div class="bh-hero-btns">
                    <a href="#transfers" class="btn bh-btn-primary">Book Transfer</a>
                    <a href="#activities" class="btn bh-btn-outline">Explore Activities</a>
                </div>
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
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="bh-footer-brand">
                        @if(!empty($ayar->firma_logo))
                            <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="{{ $ayar->site_baslik ?? '' }}" style="max-height:40px;">
                        @else
                            <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                            <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</span>
                        @endif
                    </div>
                    <p class="bh-footer-about">{{ $ayar->site_desc ?? 'We provide premium transfer services and curated holiday activities for tourists visiting Turkey\'s beautiful coast.' }}</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="bh-footer-links">
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#transfers">Transfers</a></li>
                        <li><a href="#activities">Activities</a></li>
                        <li><a href="{{ route('reviews') }}">Reviews</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Transfer Routes</h5>
                    <ul class="bh-footer-links">
                        @foreach($transferRoutes as $route)
                            <li><a href="#transfers">{{ $route->title }}</a></li>
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
                <p>&copy; {{ date('Y') }} {{ $ayar->copyright ?? ($ayar->site_baslik ?? 'Travel Center Marmaris') . '. All rights reserved.' }}</p>
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
                <a href="{{ route('privacy') }}">Privacy Policy</a>.
            </div>
            <button class="cookie-accept" onclick="acceptCookies()">Accept</button>
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
        .cookie-accept:hover { background: #e05500; transform: translateY(-1px); }
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

    <script src="{{ asset('tema/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tema/js/bootstrap.min.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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

        if (formPanel && cards.length > 0) {
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    cards.forEach(function(c) { c.classList.remove('active'); });
                    this.classList.add('active');
                    var route = this.dataset.route || '';
                    var price = this.dataset.price || '0';
                    if (routeInput) routeInput.value = route;
                    if (routeTitle) routeTitle.textContent = route;
                    var priceBar = document.getElementById('transferPriceBar');
                    var priceDisplay = document.getElementById('transferPriceDisplay');
                    if (priceBar && price > 0) {
                        priceBar.style.display = 'flex';
                        priceDisplay.textContent = '£' + parseInt(price);
                    } else if (priceBar) {
                        priceBar.style.display = 'none';
                    }
                    formPanel.classList.add('open');
                    setTimeout(function() {
                        formPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                });
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
    });
    </script>
</body>
</html>
