@extends('layouts.admin')

@section('title', 'Kontrol Paneli')

@section('content')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #0f2440 0%, #1e3a8a 50%, #6d28d9 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 20px 60px rgba(15,36,64,0.25);
    }
    .dash-hero::before { content:''; position:absolute; top:-100px; right:-80px; width:280px; height:280px; background:radial-gradient(circle, rgba(255,107,0,0.3), transparent 70%); border-radius:50%; }
    .dash-hero::after { content:''; position:absolute; bottom:-110px; left:-60px; width:240px; height:240px; background:radial-gradient(circle, rgba(16,185,129,0.22), transparent 70%); border-radius:50%; }
    .dash-hero-inner { position:relative; z-index:2; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px; }
    .dash-hero h1 { font-size:24px; font-weight:800; margin:0; }
    .dash-hero .greet { font-size:13px; opacity:0.75; margin-top:4px; }
    .dash-hero-stats { display:flex; gap:24px; flex-wrap:wrap; }
    .dash-hero-stats div { font-size:11px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.7; }
    .dash-hero-stats strong { display:block; font-size:22px; font-weight:800; margin-top:2px; opacity:1; letter-spacing:0; text-transform:none; }

    .dash-kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px; }
    .dash-kpi {
        background:#fff; border-radius:16px; padding:20px;
        box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e2e8f0;
        position:relative; overflow:hidden;
        transition:transform 0.2s;
    }
    .dash-kpi:hover { transform:translateY(-3px); }
    .dash-kpi-icon { width:46px; height:46px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; font-size:18px; color:#fff; margin-bottom:12px; }
    .dash-kpi-label { font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:#64748b; font-weight:700; }
    .dash-kpi-value { font-size:24px; font-weight:800; color:#0f172a; margin-top:4px; }
    .dash-kpi-sub { font-size:11px; color:#94a3b8; margin-top:3px; }
    .dash-kpi.green .dash-kpi-icon  { background:linear-gradient(135deg, #10b981, #059669); }
    .dash-kpi.blue .dash-kpi-icon   { background:linear-gradient(135deg, #0ea5e9, #0369a1); }
    .dash-kpi.orange .dash-kpi-icon { background:linear-gradient(135deg, #f59e0b, #d97706); }
    .dash-kpi.purple .dash-kpi-icon { background:linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .dash-kpi.pink .dash-kpi-icon   { background:linear-gradient(135deg, #ec4899, #be185d); }
    .dash-kpi.teal .dash-kpi-icon   { background:linear-gradient(135deg, #14b8a6, #0f766e); }

    .dash-card { background:#fff; border-radius:16px; padding:22px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e2e8f0; margin-bottom:22px; }
    .dash-card h3 { font-size:14px; font-weight:700; color:#0f172a; margin:0 0 14px; display:flex; align-items:center; gap:10px; }
    .dash-card h3 i { color:var(--admin-primary); }
    .dash-card .header-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
    .dash-card .header-row h3 { margin:0; }

    .dash-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .dash-row:last-child { border-bottom:none; }
    .dash-row .ic { width:36px; height:36px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; color:#fff; font-size:14px; }
    .dash-row .info { flex:1; min-width:0; }
    .dash-row .info .t { font-size:13px; font-weight:700; color:#0f172a; }
    .dash-row .info .s { font-size:11px; color:#64748b; }
    .dash-row .right { font-size:12px; color:#64748b; text-align:right; flex-shrink:0; }

    .dash-rev-card {
        background: linear-gradient(135deg, #0066cc, #6d28d9);
        color:#fff; border-radius:16px; padding:22px;
        margin-bottom:22px; position:relative; overflow:hidden;
    }
    .dash-rev-card::before { content:''; position:absolute; top:-50px; right:-50px; width:160px; height:160px; background:radial-gradient(circle, rgba(255,255,255,0.15), transparent 70%); border-radius:50%; }
    .dash-rev-card .lbl { font-size:11px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.8; }
    .dash-rev-card .val { font-size:30px; font-weight:800; margin:6px 0 4px; }
    .dash-rev-card .sub { font-size:12px; opacity:0.85; }

    .dash-stars i { color:#f59e0b; font-size:11px; }
    .dash-stars i.empty { color:#e2e8f0; }

    @media (max-width:1200px) { .dash-kpi-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px)  { .dash-kpi-grid { grid-template-columns:1fr; } }
</style>

{{-- HERO --}}
<div class="dash-hero">
    <div class="dash-hero-inner">
        <div>
            <h1>Hoş geldin, Patron! 👋</h1>
            <div class="greet">{{ \Carbon\Carbon::now()->locale('tr')->isoFormat('dddd, D MMMM YYYY') }}</div>
        </div>
        <div class="dash-hero-stats">
            <div>Toplam Ciro<strong>£{{ number_format($stats['total_revenue'], 0) }}</strong></div>
            <div>Bu Ay<strong>£{{ number_format($stats['month_revenue'], 0) }}</strong></div>
            <div>Bugün<strong>£{{ number_format($stats['today_revenue'], 0) }}</strong></div>
        </div>
    </div>
</div>

{{-- KPI ROW --}}
<div class="dash-kpi-grid">
    <div class="dash-kpi blue">
        <div class="dash-kpi-icon"><i class="fas fa-users"></i></div>
        <div class="dash-kpi-label">Toplam Müşteri</div>
        <div class="dash-kpi-value">{{ $stats['total_customers'] }}</div>
        <div class="dash-kpi-sub">+{{ $stats['today_customers'] }} bugün</div>
    </div>
    <div class="dash-kpi green">
        <div class="dash-kpi-icon"><i class="fas fa-shuttle-van"></i></div>
        <div class="dash-kpi-label">Transferler</div>
        <div class="dash-kpi-value">{{ $stats['total_transfers'] }}</div>
        <div class="dash-kpi-sub">{{ $stats['week_customers'] }} bu hafta</div>
    </div>
    <div class="dash-kpi orange">
        <div class="dash-kpi-icon"><i class="fas fa-mountain-sun"></i></div>
        <div class="dash-kpi-label">Aktiviteler</div>
        <div class="dash-kpi-value">{{ $stats['total_activities'] }}</div>
        <div class="dash-kpi-sub">{{ $stats['month_customers'] }} bu ay</div>
    </div>
    <div class="dash-kpi purple">
        <div class="dash-kpi-icon"><i class="fas fa-bell"></i></div>
        <div class="dash-kpi-label">Bekleyen</div>
        <div class="dash-kpi-value">{{ $stats['pending_reviews'] + $stats['unread_messages'] }}</div>
        <div class="dash-kpi-sub">{{ $stats['pending_reviews'] }} yorum · {{ $stats['unread_messages'] }} mesaj</div>
    </div>
</div>

{{-- CHART + UPCOMING --}}
<div class="row">
    <div class="col-lg-8 d-flex">
        <div class="dash-card flex-fill d-flex flex-column">
            <div class="header-row">
                <h3><i class="fas fa-chart-area"></i> Son 14 Gün Gelir</h3>
                <a href="{{ route('admin.earnings.index') }}" style="font-size:12px;color:var(--admin-primary);text-decoration:none;font-weight:600;">Detaylı görünüm <i class="fas fa-arrow-right" style="font-size:10px;"></i></a>
            </div>
            <div class="flex-fill" style="position:relative;min-height:240px;">
                <canvas id="dashRevenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 d-flex">
        <div class="dash-card flex-fill">
            <h3><i class="fas fa-plane-arrival"></i> Yaklaşan Karşılamalar</h3>
            @forelse($upcoming_arrivals as $a)
                <div class="dash-row">
                    <div class="ic" style="background:linear-gradient(135deg,#0ea5e9,#0369a1);"><i class="fas fa-plane"></i></div>
                    <div class="info">
                        <div class="t">{{ $a->first_name }} {{ $a->last_name }}</div>
                        <div class="s">{{ $a->arrival_flight ?? '—' }} · {{ $a->hotel_name ?: '—' }}</div>
                    </div>
                    <div class="right">
                        {{ \Carbon\Carbon::parse($a->arrival_date)->format('d M') }}<br>
                        <strong style="color:#0f172a;">{{ $a->arrival_time ?? '' }}</strong>
                    </div>
                </div>
            @empty
                <p style="color:#94a3b8;text-align:center;padding:30px 0;font-size:13px;"><i class="fas fa-plane" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.3;"></i>Önümüzdeki 7 günde karşılama yok</p>
            @endforelse
        </div>
    </div>
</div>

{{-- TOP ACTIVITIES + LATEST REVIEWS --}}
<div class="row">
    <div class="col-lg-6 d-flex">
        <div class="dash-card flex-fill">
            <h3><i class="fas fa-fire"></i> En Çok Satan Aktiviteler</h3>
            @forelse($top_activities as $i => $a)
                <div class="dash-row">
                    <div class="ic" style="background:linear-gradient(135deg,#f59e0b,#d97706);font-weight:800;">{{ $i + 1 }}</div>
                    <div class="info">
                        <div class="t">{{ ucwords($a->name) }}</div>
                        <div class="s">{{ $a->cnt }} satış</div>
                    </div>
                    <div class="right"><strong style="color:#059669;font-size:14px;">£{{ number_format($a->total, 0) }}</strong></div>
                </div>
            @empty
                <p style="color:#94a3b8;text-align:center;padding:24px 0;font-size:13px;">Henüz satış yok</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6 d-flex">
        <div class="dash-card flex-fill">
            <div class="header-row">
                <h3><i class="fas fa-comment-dots"></i> Son Yorumlar</h3>
                <a href="{{ route('admin.reviews.index') }}" style="font-size:12px;color:var(--admin-primary);text-decoration:none;font-weight:600;">Tümü <i class="fas fa-arrow-right" style="font-size:10px;"></i></a>
            </div>
            @forelse($latest_reviews as $r)
                <div class="dash-row">
                    <div class="ic" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);font-weight:800;font-size:13px;">{{ strtoupper(substr($r->name, 0, 1)) }}</div>
                    <div class="info">
                        <div class="t">{{ $r->name }}</div>
                        <div class="dash-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i > $r->rating ? 'empty' : '' }}"></i>
                            @endfor
                            <span style="color:#94a3b8;font-size:11px;margin-left:6px;">{{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color:#94a3b8;text-align:center;padding:24px 0;font-size:13px;">Henüz yorum yok</p>
            @endforelse
        </div>
    </div>
</div>

{{-- RECENT CUSTOMERS --}}
<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-clock" style="color:var(--admin-primary);margin-right:8px;"></i> Son Müşteriler</h5>
        <div>
            <a href="{{ route('admin.customers.export') }}" class="btn-admin btn-admin-success" style="font-size:13px;padding:8px 16px;">
                <i class="fas fa-download"></i> CSV İndir
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn-admin btn-admin-primary" style="font-size:13px;padding:8px 16px;">
                Tümünü Gör <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Tür</th>
                    <th>Detay</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_customers as $customer)
                <tr>
                    <td><strong>#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <span class="type-badge {{ $customer->type }}">
                            {{ $customer->type === 'transfer' ? 'Transfer' : 'Activity' }}
                        </span>
                    </td>
                    <td>{{ $customer->type === 'activity' ? $customer->activity_name : $customer->package }}</td>
                    <td>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d M Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:40px;color:var(--admin-text-light);">
                        <i class="fas fa-inbox" style="font-size:36px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                        Henüz müşteri yok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
    const labels = @json($miniLabels);
    const data = @json($miniData);
    const ctx = document.getElementById('dashRevenueChart').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0, 'rgba(109,40,217,0.35)');
    grad.addColorStop(1, 'rgba(0,102,204,0.02)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Gelir',
                data: data,
                borderColor: '#6d28d9',
                backgroundColor: grad,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#6d28d9',
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (c) => '£' + c.parsed.y.toLocaleString('tr-TR') } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => '£' + v } },
                x: { grid: { display: false } }
            }
        }
    });
})();
</script>
@endsection
