<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog — {{ $ayar->site_baslik ?? 'Travel Center Marmaris' }}</title>
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

        .blog-hero {
            background: linear-gradient(135deg, var(--bh-dark), var(--bh-primary));
            padding: 100px 0 50px;
            text-align: center;
        }
        .blog-hero h1 { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .blog-hero p { font-size: 16px; color: rgba(255,255,255,0.7); }

        .blog-section { padding: 50px 0 80px; }

        .blog-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .blog-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transform: translateY(-4px);
        }
        .blog-card-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }
        .blog-card-img-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, var(--bh-primary), var(--bh-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.3);
            font-size: 48px;
        }
        .blog-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .blog-card-date {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .blog-card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--bh-dark);
            margin-bottom: 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .blog-card-title a {
            color: inherit;
            text-decoration: none;
        }
        .blog-card-title a:hover {
            color: var(--bh-primary);
        }
        .blog-card-excerpt {
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .blog-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--bh-primary);
            text-decoration: none;
            transition: all 0.2s;
        }
        .blog-card-btn:hover {
            color: var(--bh-dark);
            gap: 10px;
        }

        .blog-pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .blog-pagination .pagination {
            gap: 6px;
        }
        .blog-pagination .page-link {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            color: var(--bh-dark);
            font-size: 14px;
            font-weight: 600;
            padding: 10px 16px;
            font-family: 'Poppins', sans-serif;
        }
        .blog-pagination .page-item.active .page-link {
            background: var(--bh-primary);
            border-color: var(--bh-primary);
            color: #fff;
        }
        .blog-pagination .page-link:hover {
            background: var(--bh-primary);
            border-color: var(--bh-primary);
            color: #fff;
        }

        .blog-empty {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }
        .blog-empty i {
            font-size: 48px;
            color: #e2e8f0;
            display: block;
            margin-bottom: 16px;
        }
        .blog-empty p {
            color: #64748b;
            font-size: 16px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .blog-hero { padding: 80px 0 40px; }
            .blog-hero h1 { font-size: 28px; }
            .blog-card-img, .blog-card-img-placeholder { height: 180px; }
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
    <div class="blog-hero">
        <div class="container">
            <h1><i class="fas fa-newspaper" style="margin-right:10px;"></i> Blog</h1>
            <p>Latest news, tips and travel guides</p>
        </div>
    </div>

    {{-- BLOG LISTING --}}
    <div class="blog-section">
        <div class="container">
            @if($bloglar->count() > 0)
            <div class="row">
                @foreach($bloglar as $blog)
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="blog-card">
                        @if(!empty($blog->resim))
                            @php
                                $imgSrc = str_contains($blog->resim, '/') ? asset($blog->resim) : asset('tema/uploads/bloglar/' . $blog->resim);
                            @endphp
                            <a href="{{ route('blog.detail', $blog->seo ?? $blog->id) }}">
                                <img src="{{ $imgSrc }}" alt="{{ $blog->baslik ?? $blog->adi ?? '' }}" class="blog-card-img">
                            </a>
                        @else
                            <a href="{{ route('blog.detail', $blog->seo ?? $blog->id) }}">
                                <div class="blog-card-img-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            </a>
                        @endif
                        <div class="blog-card-body">
                            <div class="blog-card-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $blog->tarih ? \Carbon\Carbon::parse($blog->tarih)->format('d M Y') : \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                            </div>
                            <h3 class="blog-card-title">
                                <a href="{{ route('blog.detail', $blog->seo ?? $blog->id) }}">
                                    {{ $blog->baslik ?? $blog->adi ?? 'Untitled' }}
                                </a>
                            </h3>
                            <p class="blog-card-excerpt">
                                {{ $blog->ozet ?? $blog->aciklama ?? Str::limit(strip_tags($blog->icerik ?? ''), 150) }}
                            </p>
                            <a href="{{ route('blog.detail', $blog->seo ?? $blog->id) }}" class="blog-card-btn">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($bloglar->hasPages())
            <div class="blog-pagination">
                {{ $bloglar->links() }}
            </div>
            @endif

            @else
            <div class="blog-empty">
                <i class="fas fa-newspaper"></i>
                <p>No blog posts yet. Check back soon!</p>
            </div>
            @endif
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
