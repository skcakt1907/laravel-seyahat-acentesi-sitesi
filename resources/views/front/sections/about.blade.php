<section id="about" class="bh-section">
    <div class="container">
        <div class="bh-section-header">
            <h2>About</h2>
        </div>

        <div class="row align-items-center about-row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-text">
                    <h3>Who We Are</h3>
                    <p>{{ $ayar->site_baslik ?? 'Marmaris Travel Center' }} is a locally based travel company specialising in airport transfers and curated holiday experiences along Turkey's stunning Aegean and Mediterranean coast — from Marmaris and Fethiye to Oludeniz and beyond.</p>
                    <p>We help travellers enjoy a smooth, comfortable, and memorable holiday from the moment they land. Transparent pricing in £, English-speaking support, and trusted local drivers.</p>
                    <a href="{{ route('about') }}" class="btn bh-btn-primary"><i class="fas fa-arrow-right" style="margin-right:6px;"></i> Learn More</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-stats-grid">
                    <div class="about-stat-box"><i class="fas fa-calendar-check"></i><div class="num">10+</div><div class="lbl">Years Experience</div></div>
                    <div class="about-stat-box"><i class="fas fa-users"></i><div class="num">15k+</div><div class="lbl">Happy Guests</div></div>
                    <div class="about-stat-box"><i class="fas fa-headset"></i><div class="num">24/7</div><div class="lbl">Support</div></div>
                    <div class="about-stat-box"><i class="fas fa-pound-sign"></i><div class="num">£</div><div class="lbl">Fair Pricing</div></div>
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
