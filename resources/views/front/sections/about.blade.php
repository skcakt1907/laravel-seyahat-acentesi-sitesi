@php
    $stats = $ayar && !empty($ayar->about_stats) ? json_decode($ayar->about_stats, true) : [];
    $aboutTitle = $ayar->about_title ?? 'Who We Are';
    $aboutText = $ayar->about_text ?? ($ayar->site_baslik ?? 'Marmaris Travel Center') . ' is a locally based travel company specialising in airport transfers and curated holiday experiences along Turkey\'s stunning Aegean and Mediterranean coast — from Marmaris and Fethiye to Oludeniz and beyond.';

    // Varsayılan istatistikler
    if (empty($stats) || empty($stats[0]['num'])) {
        $stats = [
            ['num' => '10+', 'label' => 'Years Experience', 'icon' => 'fas fa-calendar-check'],
            ['num' => '15k+', 'label' => 'Happy Guests', 'icon' => 'fas fa-users'],
            ['num' => '24/7', 'label' => 'Support', 'icon' => 'fas fa-headset'],
            ['num' => \App\Helpers\SiteCurrency::symbol(), 'label' => 'Fair Pricing', 'icon' => 'fas fa-pound-sign'],
        ];
    }
@endphp

<section id="about" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <h2>{{ __('About') }}</h2>
        </div>

        <div class="row align-items-center about-row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                @if(!empty($ayar->about_image))
                    <img src="{{ asset('tema/uploads/' . $ayar->about_image) }}" alt="{{ $aboutTitle }}" style="width:100%;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.1);">
                @else
                    <div class="about-text">
                        <h3>{{ $aboutTitle }}</h3>
                        <p>{{ $aboutText }}</p>
                        <a href="{{ route('about') }}" class="btn bh-btn-primary"><i class="fas fa-arrow-right" style="margin-right:6px;"></i> Learn More</a>
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                @if(!empty($ayar->about_image))
                    <div class="about-text">
                        <h3>{{ $aboutTitle }}</h3>
                        <p>{{ $aboutText }}</p>
                        <a href="{{ route('about') }}" class="btn bh-btn-primary"><i class="fas fa-arrow-right" style="margin-right:6px;"></i> Learn More</a>
                    </div>
                @endif
                <div class="about-stats-grid" @if(!empty($ayar->about_image)) style="margin-top:24px;" @endif>
                    @foreach($stats as $stat)
                        @if(!empty($stat['num']))
                        <div class="about-stat-box">
                            <i class="{{ $stat['icon'] ?? 'fas fa-star' }}"></i>
                            <div class="num">{{ $stat['num'] }}</div>
                            <div class="lbl">{{ $stat['label'] ?? '' }}</div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<style>
#about .about-text h3 { font-size: 26px; font-weight: 800; color: var(--bh-dark); margin-bottom: 16px; }
#about .about-text p { font-size: 15px; line-height: 1.8; color: #475569; margin-bottom: 14px; }
[data-theme="dark"] #about .about-text h3 { color: #e2e8f0; }
[data-theme="dark"] #about .about-text p { color: #cbd5e1; }

#about .about-stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
#about .about-stat-box {
    background: linear-gradient(135deg, var(--bh-primary), var(--bh-dark));
    color: #fff;
    padding: 28px 18px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}
#about .about-stat-box:hover { transform: translateY(-4px); }
#about .about-stat-box i { font-size: 26px; color: var(--bh-secondary); margin-bottom: 10px; }
#about .about-stat-box .num { font-size: 30px; font-weight: 800; line-height: 1; }
#about .about-stat-box .lbl { font-size: 13px; opacity: 0.85; margin-top: 6px; }

@media (max-width: 576px) {
    #about .about-stats-grid { grid-template-columns: 1fr 1fr; }
}
</style>
