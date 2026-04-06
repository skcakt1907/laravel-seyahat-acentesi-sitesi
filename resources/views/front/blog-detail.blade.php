<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->seo_baslik ?? $blog->baslik ?? $blog->adi ?? 'Blog' }} — {{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</title>
    @if(!empty($blog->description))
    <meta name="description" content="{{ $blog->description }}">
    @endif
    @if(!empty($blog->keywords))
    <meta name="keywords" content="{{ $blog->keywords }}">
    @endif
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

        .blog-detail-hero {
            background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary));
            padding: 100px 0 50px;
            text-align: center;
        }
        .blog-detail-hero h1 {
            font-size: 34px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.3;
        }
        .blog-detail-hero .blog-meta {
            font-size: 14px;
            color: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        .blog-detail-hero .blog-meta i {
            margin-right: 5px;
        }

        .blog-detail-section { padding: 50px 0 80px; }

        .blog-content-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .blog-featured-img {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            display: block;
        }
        .blog-content-body {
            padding: 40px;
        }
        .blog-content-body h2,
        .blog-content-body h3,
        .blog-content-body h4 {
            color: var(--bh-dark);
            margin-top: 28px;
            margin-bottom: 14px;
        }
        .blog-content-body p {
            font-size: 15px;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 16px;
        }
        .blog-content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 16px 0;
        }
        .blog-content-body ul,
        .blog-content-body ol {
            color: #334155;
            font-size: 15px;
            line-height: 1.8;
            padding-left: 24px;
            margin-bottom: 16px;
        }
        .blog-content-body blockquote {
            border-left: 4px solid var(--bh-primary);
            padding: 16px 24px;
            margin: 20px 0;
            background: #f8fafc;
            border-radius: 0 10px 10px 0;
            font-style: italic;
            color: #475569;
        }

        .blog-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--bh-primary);
            text-decoration: none;
            margin-bottom: 24px;
            transition: all 0.2s;
        }
        .blog-back-link:hover {
            color: var(--bh-dark);
            gap: 12px;
        }

        .blog-tags {
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        .blog-tags span {
            display: inline-block;
            background: #f1f5f9;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        @media (max-width: 768px) {
            .blog-detail-hero { padding: 80px 0 40px; }
            .blog-detail-hero h1 { font-size: 24px; }
            .blog-content-body { padding: 24px; }
            .blog-featured-img { max-height: 300px; }
        }
    </style>
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
                    <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</span>
                @endif
            </a>
            <nav class="bh-nav" id="bhNav">
                <a href="{{ route('anasayfa') }}">Home</a>
                <a href="{{ route('anasayfa') }}#transfers">Transfers</a>
                <a href="{{ route('anasayfa') }}#activities">Activities</a>
                <a href="{{ route('blog.index') }}" style="color:var(--bh-primary);">Blog</a>
                <a href="{{ route('reviews') }}">Reviews</a>
                <a href="{{ route('anasayfa') }}#footer">Contact</a>
            </nav>
            <button class="bh-hamburger" id="bhHamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    {{-- HERO --}}
    <div class="blog-detail-hero">
        <div class="container">
            <h1>{{ $blog->baslik ?? $blog->adi ?? 'Blog Post' }}</h1>
            <div class="blog-meta">
                <span>
                    <i class="fas fa-calendar-alt"></i>
                    {{ $blog->tarih ? \Carbon\Carbon::parse($blog->tarih)->format('d M Y') : \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                </span>
                @if($blog->hit > 0)
                <span>
                    <i class="fas fa-eye"></i>
                    {{ $blog->hit }} views
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- BLOG DETAIL --}}
    <div class="blog-detail-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <a href="{{ route('blog.index') }}" class="blog-back-link">
                        <i class="fas fa-arrow-left"></i> Back to Blog
                    </a>

                    <div class="blog-content-card">
                        @if(!empty($blog->resim))
                            @php
                                $imgSrc = str_contains($blog->resim, '/') ? asset($blog->resim) : asset('tema/uploads/bloglar/' . $blog->resim);
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $blog->baslik ?? $blog->adi ?? '' }}" class="blog-featured-img">
                        @endif

                        <div class="blog-content-body">
                            {!! $blog->icerik ?? $blog->aciklama ?? '' !!}

                            @if(!empty($blog->keywords))
                            <div class="blog-tags">
                                @foreach(explode(',', $blog->keywords) as $tag)
                                    <span>{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('blog.index') }}" class="blog-back-link">
                            <i class="fas fa-arrow-left"></i> Back to Blog
                        </a>
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
                            <span class="bh-logo-text">{{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</span>
                        @endif
                    </div>
                    <p class="bh-footer-about">{{ $ayar->site_desc ?? '' }}</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="bh-footer-links">
                        <li><a href="{{ route('anasayfa') }}">Home</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('reviews') }}">Reviews</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Contact Us</h5>
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
</body>
</html>
