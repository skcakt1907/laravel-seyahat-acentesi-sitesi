<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Reviews — {{ $ayar->site_baslik ?? 'Marmaris Travel Center' }}</title>
    <link rel="canonical" href="{{ url('/reviews') }}">
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

        .reviews-hero {
            background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary));
            padding: 100px 0 50px;
            text-align: center;
        }
        .reviews-hero h1 { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .reviews-hero p { font-size: 16px; color: rgba(255,255,255,0.7); }

        .reviews-section { padding: 50px 0 80px; }

        /* Google-style review card */
        .review-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            transition: all 0.2s;
        }
        .review-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .review-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .review-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bh-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .review-meta strong { display: block; font-size: 15px; color: var(--bh-dark); }
        .review-meta span { font-size: 13px; color: #64748b; }
        .review-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 10px;
        }
        .review-stars i { color: #f59e0b; font-size: 14px; }
        .review-stars i.empty { color: #e2e8f0; }
        .review-text { font-size: 14px; line-height: 1.7; color: #334155; }
        .review-date { font-size: 12px; color: #94a3b8; margin-top: 10px; }

        /* Review Form */
        .review-form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            position: sticky;
            top: 90px;
        }
        .review-form-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--bh-dark);
            margin-bottom: 6px;
        }
        .review-form-card .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .rf-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--bh-dark);
            margin-bottom: 6px;
        }
        .rf-input {
            width: 100%;
            height: 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: var(--bh-dark);
            transition: all 0.2s;
        }
        .rf-input:focus {
            border-color: var(--bh-primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
        }
        textarea.rf-input { height: auto; padding: 12px 14px; resize: vertical; }

        /* Star rating picker */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            gap: 4px;
            margin-bottom: 4px;
        }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 28px;
            color: #e2e8f0;
            cursor: pointer;
            transition: color 0.15s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f59e0b;
        }

        .btn-submit-review {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--bh-primary);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit-review:hover {
            background: var(--bh-dark);
            transform: translateY(-1px);
        }

        .alert-review {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Summary bar */
        .review-summary {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .review-summary-score {
            text-align: center;
            min-width: 100px;
        }
        .review-summary-score .big { font-size: 48px; font-weight: 800; color: var(--bh-dark); line-height: 1; }
        [data-theme="dark"] .review-summary { background: #1a2332 !important; border-color: #2a3548 !important; }
        [data-theme="dark"] .review-summary-score .big { color: #ffffff !important; }
        [data-theme="dark"] .review-summary-score .count,
        [data-theme="dark"] .review-summary-text,
        [data-theme="dark"] .review-summary p { color: #cbd5e1 !important; }
        [data-theme="dark"] .review-card { background: #1a2332 !important; border-color: #2a3548 !important; }
        [data-theme="dark"] .review-meta strong { color: #ffffff !important; }
        [data-theme="dark"] .review-meta span,
        [data-theme="dark"] .review-text,
        [data-theme="dark"] .review-date { color: #cbd5e1 !important; }
        [data-theme="dark"] .review-form-card { background: #1a2332 !important; border-color: #2a3548 !important; }
        [data-theme="dark"] .review-form-card h3,
        [data-theme="dark"] .review-form-card h3 i { color: #ffffff !important; }
        [data-theme="dark"] .review-form-card .subtitle { color: #94a3b8 !important; }
        [data-theme="dark"] .rf-label { color: #e2e8f0 !important; }
        [data-theme="dark"] .rf-input { background: #0f1825 !important; border-color: #2a3548 !important; color: #e2e8f0 !important; }
        [data-theme="dark"] .rf-input::placeholder { color: #64748b !important; }
        [data-theme="dark"] .btn-submit-review { background: var(--bh-secondary) !important; color: #fff !important; }
        [data-theme="dark"] .btn-submit-review:hover { background: #0066cc !important; }
        .review-summary-score .stars { color: #f59e0b; font-size: 16px; margin: 6px 0 4px; }
        .review-summary-score .count { font-size: 13px; color: #64748b; }

        @media (max-width: 768px) {
            .reviews-hero { padding: 80px 0 40px; }
            .reviews-hero h1 { font-size: 28px; }
            .review-summary { flex-direction: column; text-align: center; }
        }
    </style>
    <script>
    (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.setAttribute('data-theme','dark');}})();
    </script>
    @include('front.partials.rtl')
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
                <a href="{{ route('anasayfa') }}">{{ __('Home') }}</a>
                <a href="{{ route('anasayfa') }}#transfers">{{ __('Transfers') }}</a>
                <a href="{{ route('anasayfa') }}#activities">{{ __('Excursions') }}</a>
                <a href="{{ route('reviews') }}" style="color:var(--bh-primary);">{{ __('Reviews') }}</a>
                <a href="{{ route('anasayfa') }}#footer">{{ __('Contact') }}</a>
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

    {{-- HERO --}}
    <div class="reviews-hero">
        <div class="container">
            <h1><i class="fas fa-star" style="color:#f59e0b;margin-right:8px;"></i> {{ __('Guest Reviews') }}</h1>
            <p>{{ __('See what our customers say about their experience') }}</p>
        </div>
    </div>

    {{-- REVIEWS SECTION --}}
    <div class="reviews-section">
        <div class="container">
            @if(session('success'))
                <div class="alert-review"><i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ session('success') }}</div>
            @endif

            {{-- Summary --}}
            @if($reviews->count() > 0)
            @php
                $avgRating = $reviews->avg('rating');
                $totalReviews = $reviews->count();
            @endphp
            <div class="review-summary">
                <div class="review-summary-score">
                    <div class="big">{{ number_format($avgRating, 1) }}</div>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($avgRating) ? '' : ' empty' }}" style="{{ $i > round($avgRating) ? 'color:#e2e8f0;' : '' }}"></i>
                        @endfor
                    </div>
                    <div class="count">{{ $totalReviews }} review{{ $totalReviews > 1 ? 's' : '' }}</div>
                </div>
                <div style="flex:1;font-size:15px;color:#334155;line-height:1.7;">
                    Our guests rate us <strong>{{ number_format($avgRating, 1) }} out of 5</strong> based on {{ $totalReviews }} reviews. We're proud to provide excellent service to tourists from the UK and Scotland visiting Turkey.
                </div>
            </div>
            @endif

            <div class="row">
                {{-- Reviews List --}}
                <div class="col-lg-8 mb-4">
                    @forelse($reviews as $review)
                    <div class="review-card">
                        <div class="review-top">
                            <div class="review-avatar">{{ strtoupper(substr($review->name, 0, 1)) }}</div>
                            <div class="review-meta">
                                <strong>{{ $review->name }}</strong>
                                <span>{{ $review->location ?? 'Guest' }}</span>
                            </div>
                        </div>
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' empty' }}" style="{{ $i > $review->rating ? 'color:#e2e8f0;' : '' }}"></i>
                            @endfor
                        </div>
                        <div class="review-text">{{ $review->comment }}</div>
                        <div class="review-date"><i class="fas fa-clock" style="margin-right:4px;"></i> {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div class="review-card text-center" style="padding:50px;">
                        <i class="fas fa-comments" style="font-size:40px;color:#e2e8f0;display:block;margin-bottom:14px;"></i>
                        <p style="color:#64748b;margin:0;">{{ __('No reviews yet. Be the first to share your experience!') }}</p>
                    </div>
                    @endforelse
                </div>

                {{-- Review Form --}}
                <div class="col-lg-4">
                    <div class="review-form-card">
                        <h3><i class="fas fa-pen" style="color:var(--bh-primary);margin-right:8px;font-size:18px;"></i> {{ __('Write a Review') }}</h3>
                        <p class="subtitle">{{ __('Share your experience with others') }}</p>

                        @if($errors->any())
                            <div style="padding:12px;border-radius:8px;background:#fef2f2;color:#dc2626;font-size:13px;margin-bottom:16px;border:1px solid #fecaca;">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('reviews.submit') }}" method="POST">
                            @csrf
                            <div style="position:absolute;left:-9999px;"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                            <div class="mb-3">
                                <label class="rf-label">{{ __('Your Name *') }}</label>
                                <input type="text" name="name" class="rf-input" value="{{ old('name') }}" placeholder="{{ __('John Smith') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="rf-label">{{ __('Email *') }}</label>
                                <input type="email" name="email" class="rf-input" value="{{ old('email') }}" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="rf-label">{{ __('Location') }}</label>
                                <input type="text" name="location" class="rf-input" value="{{ old('location') }}" placeholder="{{ __('London, UK') }}">
                            </div>
                            <div class="mb-3">
                                <label class="rf-label">{{ __('Rating *') }}</label>
                                <div class="star-rating">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating', 5) == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="rf-label">{{ __('Your Review *') }}</label>
                                <textarea name="comment" class="rf-input" rows="4" placeholder="{{ __('Tell us about your experience...') }}" required>{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="btn-submit-review">
                                <i class="fas fa-paper-plane" style="margin-right:6px;"></i> Submit Review
                            </button>
                        </form>
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
                    <h5>{{ __('Quick Links') }}</h5>
                    <ul class="bh-footer-links">
                        <li><a href="{{ route('anasayfa') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('reviews') }}">{{ __('Reviews') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('Contact Us') }}</h5>
                    <ul class="bh-footer-contact">
                        <li><i class="fas fa-phone-alt"></i> {{ $ayar->firma_telefon ?? '' }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $ayar->firma_email ?? '' }}</li>
                    </ul>
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
