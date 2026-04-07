<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->title }} — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <meta name="description" content="{{ Str::limit($activity->description, 160) }}">
    <meta property="og:title" content="{{ $activity->title }} — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}">
    <meta property="og:description" content="{{ Str::limit($activity->description, 160) }}">
    <meta property="og:type" content="product">
    @if($activity->image)<meta property="og:image" content="{{ asset('tema/uploads/activities/' . $activity->image) }}">@endif
    <link rel="canonical" href="{{ url('/activity/' . $activity->slug) }}">
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

        body { background: #f4f7fb; }

        /* Hero Banner */
        .detail-hero {
            position: relative;
            height: 380px;
            overflow: hidden;
        }
        .detail-hero img,
        .detail-hero .placeholder-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .detail-hero .placeholder-bg {
            background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary));
        }
        .detail-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(11,29,51,0.85) 0%, rgba(11,29,51,0.2) 60%);
        }
        .detail-hero-content {
            position: absolute;
            bottom: 36px;
            left: 0; right: 0;
            z-index: 2;
        }
        .detail-hero-content h1 {
            font-size: 40px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
        }
        .detail-hero-content .sub {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
        }
        .detail-hero-content .crumbs {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 12px;
        }
        .detail-hero-content .crumbs a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
        }
        .detail-hero-content .crumbs a:hover { color: #fff; }
        .detail-hero-content .crumbs .sep { font-size: 10px; color: rgba(255,255,255,0.3); }

        /* Main Body */
        .detail-body { padding: 40px 0 80px; }

        .detail-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        /* Gallery — main + side thumbnails like reference */
        .dt-gallery {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }
        .dt-gallery-main {
            flex: 1;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 320px;
        }
        .dt-gallery-main img {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            transition: opacity 0.35s;
        }
        .dt-gallery-main .placeholder-img {
            font-size: 56px;
            color: #cbd5e1;
        }
        .dt-gallery-thumbs {
            width: 88px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
        }
        .dt-gallery-thumb {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            opacity: 0.55;
            transition: all 0.25s;
            background: none;
            padding: 0;
        }
        .dt-gallery-thumb:hover { opacity: 0.85; }
        .dt-gallery-thumb.active {
            border-color: var(--bh-primary);
            opacity: 1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .dt-gallery-thumb img { width: 100%; height: 64px; object-fit: cover; display: block; }

        /* Tabs */
        .detail-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 0;
        }
        .detail-tab {
            padding: 14px 22px;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            background: none;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        .detail-tab:hover { color: var(--bh-dark); }
        .detail-tab.active {
            color: var(--bh-primary);
            border-bottom-color: var(--bh-primary);
        }
        .tab-panel { display: none; padding: 22px 0 0; }
        .tab-panel.active { display: block; }

        .tab-panel h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--bh-dark);
            margin: 20px 0 10px;
        }
        .tab-panel h3:first-child { margin-top: 0; }
        .tab-panel p {
            font-size: 15px;
            line-height: 1.85;
            color: #334155;
        }
        .tab-panel ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .tab-panel ul li {
            padding: 8px 0;
            font-size: 14px;
            color: #334155;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .tab-panel ul li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #10b981;
            font-size: 12px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        /* Sidebar */
        .detail-sidebar { position: sticky; top: 90px; }

        .sidebar-price-box {
            background: linear-gradient(135deg, var(--bh-primary), var(--bh-secondary));
            border-radius: 14px;
            padding: 22px;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar-price-box .price {
            font-size: 34px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }
        .sidebar-price-box .price-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
        }
        .sidebar-price-box .badge-label {
            display: inline-block;
            background: rgba(0,0,0,0.2);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 10px;
        }

        .sidebar-info {
            list-style: none;
            padding: 0;
            margin: 0 0 20px;
        }
        .sidebar-info li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
        }
        .sidebar-info li:last-child { border-bottom: none; }
        .sidebar-info li i { color: #10b981; width: 18px; text-align: center; }

        .sidebar-form .form-control {
            height: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding-left: 42px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: var(--bh-dark);
            transition: all 0.2s;
        }
        .sidebar-form .form-control:focus {
            border-color: var(--bh-primary);
            box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
            outline: none;
        }
        .sidebar-form .form-control::placeholder { color: #94a3b8; }

        .btn-buy {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: var(--bh-secondary);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-buy:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255,107,0,0.3);
        }

        @media (max-width: 991px) {
            .detail-hero { height: 280px; }
            .detail-hero-content h1 { font-size: 28px; }
        }
        @media (max-width: 768px) {
            .detail-hero { height: 220px; }
            .detail-hero-content h1 { font-size: 24px; }
            .dt-gallery { flex-direction: column; }
            .dt-gallery-thumbs { flex-direction: row; width: 100%; max-height: none; overflow-x: auto; }
            .dt-gallery-thumb { width: 70px; flex-shrink: 0; }
            .dt-gallery-thumb img { height: 48px; }
        }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
</head>
<body>

    {{-- HEADER --}}
    <header class="bh-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('anasayfa') }}" class="bh-logo">
                @if(!empty($ayar->firma_logo))
                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="" style="max-height:42px;">
                @else
                    <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                    <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
                @endif
            </a>
            <nav class="bh-nav" id="bhNav">
                <a href="{{ route('anasayfa') }}">Home</a>
                <a href="{{ route('anasayfa') }}#transfers">Transfers</a>
                <a href="{{ route('anasayfa') }}#activities">Activities</a>
                <a href="{{ route('anasayfa') }}#why-us">Why Us</a>
                <a href="{{ route('anasayfa') }}#footer">Contact</a>
            </nav>
            <div class="d-flex align-items-center">
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

    {{-- HERO BANNER --}}
    <div class="detail-hero">
        @if($activity->image)
            <img src="{{ asset('tema/uploads/activities/' . $activity->image) }}" alt="{{ $activity->title }}">
        @else
            <div class="placeholder-bg"></div>
        @endif
        <div class="detail-hero-content">
            <div class="container">
                <div class="crumbs">
                    <a href="{{ route('anasayfa') }}">Home</a>
                    <span class="sep"><i class="fas fa-chevron-right"></i></span>
                    <a href="{{ route('anasayfa') }}#activities">Activities</a>
                    <span class="sep"><i class="fas fa-chevron-right"></i></span>
                    <span style="color:#fff;">{{ $activity->title }}</span>
                </div>
                <h1>{{ $activity->title }}</h1>
                <p class="sub">{{ Str::limit($activity->description, 120) }}</p>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="detail-body">
        <div class="container">
            <div class="row">
                {{-- LEFT --}}
                <div class="col-lg-8 mb-4">
                    <div class="detail-card" style="padding:28px;">

                        {{-- Gallery with thumbnails --}}
                        @php
                            $allImages = [];
                            if ($activity->image) $allImages[] = $activity->image;
                            foreach ($gallery as $g) $allImages[] = $g;
                        @endphp

                        <div class="dt-gallery">
                            <div class="dt-gallery-main">
                                @if(count($allImages) > 0)
                                    <img src="{{ asset('tema/uploads/activities/' . $allImages[0]) }}" alt="{{ $activity->title }}" id="mainImg">
                                @else
                                    <div class="placeholder-img"><i class="fas fa-mountain-sun"></i></div>
                                @endif
                            </div>
                            @if(count($allImages) > 1)
                            <div class="dt-gallery-thumbs">
                                @foreach($allImages as $idx => $img)
                                    <button class="dt-gallery-thumb {{ $idx === 0 ? 'active' : '' }}" data-src="{{ asset('tema/uploads/activities/' . $img) }}">
                                        <img src="{{ asset('tema/uploads/activities/' . $img) }}" alt="">
                                    </button>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Tabs --}}
                        <div class="detail-tabs">
                            <button class="detail-tab active" data-tab="desc"><i class="fas fa-align-left" style="margin-right:6px;"></i> Description</button>
                            <button class="detail-tab" data-tab="info"><i class="fas fa-info-circle" style="margin-right:6px;"></i> Booking Info</button>
                        </div>

                        <div class="tab-panel active" id="tab-desc">
                            <h3>About This Activity</h3>
                            <p>{!! nl2br(e($activity->description)) !!}</p>
                        </div>

                        <div class="tab-panel" id="tab-info">
                            <h3>Booking Information</h3>
                            <ul>
                                <li>Hotel pickup & drop-off included</li>
                                <li>Free cancellation up to 24 hours before</li>
                                <li>Instant confirmation after payment</li>
                                <li>English-speaking support available</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <div class="col-lg-4">
                    <div class="detail-sidebar">
                        <div class="detail-card" style="padding:28px;">
                            {{-- Price --}}
                            <div class="sidebar-price-box">
                                @if($activity->badge)
                                    <span class="badge-label">{{ $activity->badge }}</span>
                                @endif
                                @if($activity->price > 0)
                                    <div class="price">£{{ number_format($activity->price, 0) }}</div>
                                    <div class="price-sub">per person</div>
                                @else
                                    <div class="price">Contact Us</div>
                                    <div class="price-sub">for pricing</div>
                                @endif
                            </div>

                            <ul class="sidebar-info">
                                <li><i class="fas fa-check-circle"></i> Licensed & insured operation</li>
                                <li><i class="fas fa-check-circle"></i> 24/7 customer support</li>
                                <li><i class="fas fa-check-circle"></i> Free cancellation (24h)</li>
                                <li><i class="fas fa-check-circle"></i> Secure online payment</li>
                                <li><i class="fas fa-check-circle"></i> English-speaking guides</li>
                            </ul>

                            <form action="{{ route('activity.buy', $activity->slug) }}" method="POST" class="sidebar-form bh-form">
                                @csrf
                                <input type="hidden" name="activity_name" value="{{ $activity->title }}">
                                <div class="mb-3">
                                    <div class="bh-input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="bh-input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="bh-input-group">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="bh-input-group">
                                        <i class="fas fa-phone"></i>
                                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn-buy">
                                    <i class="fas fa-bolt"></i>
                                    Quick Buy
                                    @if($activity->price > 0) — £{{ number_format($activity->price, 0) }} @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bh-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="bh-footer-brand">
                        @if(!empty($ayar->firma_logo))
                            <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" alt="" style="max-height:40px;">
                        @else
                            <span class="bh-logo-icon"><i class="fas fa-sun"></i></span>
                            <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</span>
                        @endif
                    </div>
                    <p class="bh-footer-about">{{ $ayar->site_desc ?? '' }}</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="bh-footer-links">
                        <li><a href="{{ route('anasayfa') }}">Home</a></li>
                        <li><a href="{{ route('anasayfa') }}#transfers">Transfers</a></li>
                        <li><a href="{{ route('anasayfa') }}#activities">Activities</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
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
                        @if(!empty($ayar->whatsapp))<a href="https://wa.me/{{ $ayar->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bh-footer-bottom">
            <div class="container text-center">
                <p>&copy; {{ date('Y') }} {{ $ayar->copyright ?? ($ayar->site_baslik ?? '') }}</p>
            </div>
        </div>
    </footer>

    @if(!empty($ayar->whatsapp))
    <a href="https://wa.me/{{ $ayar->whatsapp }}" class="bh-wa-float" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif

    <script src="{{ asset('tema/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tema/js/bootstrap.min.js') }}"></script>
    <script>
    (function() {
        var hamburger = document.getElementById('bhHamburger');
        var nav = document.getElementById('bhNav');
        if (hamburger && nav) {
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                nav.classList.toggle('open');
            });
        }

        var header = document.querySelector('.bh-header');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 80) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });

        // Gallery thumbnails
        var mainImg = document.getElementById('mainImg');
        var thumbs = document.querySelectorAll('.dt-gallery-thumb');
        thumbs.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                thumbs.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                if (mainImg) {
                    mainImg.style.opacity = '0';
                    setTimeout(function() {
                        mainImg.src = thumb.dataset.src;
                        mainImg.style.opacity = '1';
                    }, 200);
                }
            });
        });

        // Tabs
        var tabBtns = document.querySelectorAll('.detail-tab');
        var tabPanels = document.querySelectorAll('.tab-panel');
        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                tabBtns.forEach(function(b) { b.classList.remove('active'); });
                tabPanels.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                var target = document.getElementById('tab-' + this.dataset.tab);
                if (target) target.classList.add('active');
            });
        });
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
