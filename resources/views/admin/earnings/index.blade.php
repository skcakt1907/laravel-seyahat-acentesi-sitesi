@extends('layouts.admin')

@section('title', 'Kazançlarım')

@section('content')
<style>
    .er-hero {
        background: linear-gradient(135deg, #0f2440 0%, #1e3a8a 50%, #6d28d9 100%);
        border-radius: 20px;
        padding: 32px 36px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 20px 60px rgba(15,36,64,0.25);
    }
    .er-hero::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(255,107,0,0.35), transparent 70%);
        border-radius: 50%;
    }
    .er-hero::after {
        content: '';
        position: absolute;
        bottom: -100px; left: -60px;
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(16,185,129,0.25), transparent 70%);
        border-radius: 50%;
    }
    .er-hero-inner { position: relative; z-index: 2; }
    .er-hero-label { font-size: 13px; text-transform: uppercase; letter-spacing: 0.15em; opacity: 0.7; margin-bottom: 8px; }
    .er-hero-amount { font-size: 56px; font-weight: 900; line-height: 1; margin-bottom: 12px; }
    .er-hero-amount small { font-size: 24px; opacity: 0.7; font-weight: 600; }
    .er-hero-meta { display: flex; gap: 28px; flex-wrap: wrap; margin-top: 18px; }
    .er-hero-meta div { font-size: 13px; opacity: 0.85; }
    .er-hero-meta strong { display: block; font-size: 18px; font-weight: 700; margin-top: 2px; }
    .er-growth { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 700; }
    .er-growth.up { background: rgba(16,185,129,0.25); color: #6ee7b7; }
    .er-growth.down { background: rgba(239,68,68,0.25); color: #fca5a5; }

    .er-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .er-kpi {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s;
    }
    .er-kpi:hover { transform: translateY(-3px); }
    .er-kpi-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-size: 20px;
        color: #fff;
        margin-bottom: 14px;
    }
    .er-kpi-label { font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600; }
    .er-kpi-value { font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px; }
    .er-kpi-sub { font-size: 12px; color: #94a3b8; margin-top: 4px; }
    .er-kpi.green .er-kpi-icon { background: linear-gradient(135deg, #10b981, #059669); }
    .er-kpi.blue  .er-kpi-icon { background: linear-gradient(135deg, #0ea5e9, #0369a1); }
    .er-kpi.orange .er-kpi-icon{ background: linear-gradient(135deg, #f59e0b, #d97706); }
    .er-kpi.purple .er-kpi-icon{ background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .er-kpi.red .er-kpi-icon   { background: linear-gradient(135deg, #ef4444, #b91c1c); }
    .er-kpi.teal .er-kpi-icon  { background: linear-gradient(135deg, #14b8a6, #0f766e); }

    .er-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .er-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
    .er-card-header h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px; }
    .er-card-header h3 i { color: var(--admin-primary); }
    .er-tabs { display: flex; gap: 6px; background: #f1f5f9; padding: 4px; border-radius: 10px; }
    .er-tab {
        padding: 6px 14px; font-size: 12px; font-weight: 600;
        border: none; background: transparent; color: #64748b;
        border-radius: 7px; cursor: pointer; transition: all 0.2s;
    }
    .er-tab.active { background: #fff; color: var(--admin-primary); box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

    .er-table { width: 100%; border-collapse: collapse; }
    .er-table th {
        text-align: left; padding: 10px 12px; font-size: 12px;
        color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;
        font-weight: 700; border-bottom: 2px solid #e2e8f0;
    }
    .er-table td { padding: 12px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .er-table tbody tr:hover { background: #f8fafc; }
    .er-table .amount { font-weight: 700; color: #059669; }
    .er-status { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .er-status.paid, .er-status.success { background: #dcfce7; color: #166534; }
    .er-status.pending { background: #fef3c7; color: #92400e; }
    .er-status.failed  { background: #fee2e2; color: #991b1b; }
    .er-status.refunded{ background: #e0e7ff; color: #3730a3; }

    .er-progress {
        height: 8px; background: #f1f5f9; border-radius: 50px; overflow: hidden;
    }
    .er-progress-bar {
        height: 100%; border-radius: 50px;
        background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary));
        transition: width 0.6s ease;
    }
    .er-provider-row { padding: 14px 0; border-bottom: 1px solid #f1f5f9; }
    .er-provider-row:last-child { border-bottom: none; }
    .er-provider-name { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 14px; font-weight: 600; color: #0f172a; }
    .er-provider-meta { font-size: 12px; color: #64748b; }

    .er-top-customer { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .er-top-customer:last-child { border-bottom: none; }
    .er-top-customer .avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; flex-shrink: 0;
    }
    .er-top-customer .info { flex: 1; min-width: 0; }
    .er-top-customer .name { font-size: 14px; font-weight: 700; color: #0f172a; }
    .er-top-customer .email { font-size: 12px; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .er-top-customer .total { font-size: 16px; font-weight: 800; color: #059669; }

    @media (max-width: 991px) {
        .er-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .er-hero-amount { font-size: 40px; }
    }
    @media (max-width: 576px) {
        .er-kpi-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- HERO --}}
<div class="er-hero">
    <div class="er-hero-inner">
        <div class="er-hero-label"><i class="fas fa-coins"></i> Toplam Kazanç</div>
        <div class="er-hero-amount">{{ $currency }}{{ number_format($totalRevenue, 2) }}</div>
        <div>
            @if($monthGrowth >= 0)
                <span class="er-growth up"><i class="fas fa-arrow-trend-up"></i> %{{ $monthGrowth }} bu ay</span>
            @else
                <span class="er-growth down"><i class="fas fa-arrow-trend-down"></i> %{{ $monthGrowth }} bu ay</span>
            @endif
            <span style="margin-left:10px;font-size:13px;opacity:0.8;">Geçen ay: {{ $currency }}{{ number_format($lastMonthRevenue, 2) }}</span>
        </div>
        <div class="er-hero-meta">
            <div>Bugün<strong>{{ $currency }}{{ number_format($todayRevenue, 2) }}</strong></div>
            <div>Bu Hafta<strong>{{ $currency }}{{ number_format($weekRevenue, 2) }}</strong></div>
            <div>Bu Ay<strong>{{ $currency }}{{ number_format($monthRevenue, 2) }}</strong></div>
            <div>Bu Yıl<strong>{{ $currency }}{{ number_format($yearRevenue, 2) }}</strong></div>
        </div>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="er-kpi-grid">
    <div class="er-kpi green">
        <div class="er-kpi-icon"><i class="fas fa-check-circle"></i></div>
        <div class="er-kpi-label">Başarılı İşlem</div>
        <div class="er-kpi-value">{{ number_format($successCount) }}</div>
        <div class="er-kpi-sub">%{{ $successRate }} başarı oranı</div>
    </div>
    <div class="er-kpi orange">
        <div class="er-kpi-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="er-kpi-label">Bekleyen Tutar</div>
        <div class="er-kpi-value">{{ $currency }}{{ number_format($pendingAmount, 2) }}</div>
        <div class="er-kpi-sub">{{ $pendingCount }} işlem</div>
    </div>
    <div class="er-kpi red">
        <div class="er-kpi-icon"><i class="fas fa-times-circle"></i></div>
        <div class="er-kpi-label">Başarısız Tutar</div>
        <div class="er-kpi-value">{{ $currency }}{{ number_format($failedAmount, 2) }}</div>
        <div class="er-kpi-sub">{{ $failedCount }} işlem</div>
    </div>
    <div class="er-kpi purple">
        <div class="er-kpi-icon"><i class="fas fa-chart-line"></i></div>
        <div class="er-kpi-label">Ort. Sepet Tutarı</div>
        <div class="er-kpi-value">{{ $currency }}{{ number_format($avgOrderValue, 2) }}</div>
        <div class="er-kpi-sub">{{ $totalTxCount }} toplam işlem</div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row">
    <div class="col-lg-8 d-flex">
        <div class="er-card flex-fill d-flex flex-column">
            <div class="er-card-header">
                <h3><i class="fas fa-chart-area"></i> Gelir Trendi</h3>
                <div class="er-tabs">
                    <button class="er-tab active" data-chart="daily">Son 30 Gün</button>
                    <button class="er-tab" data-chart="monthly">Son 12 Ay</button>
                </div>
            </div>
            <div class="flex-fill" style="position:relative;min-height:340px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 d-flex">
        <div class="er-card flex-fill">
            <div class="er-card-header">
                <h3><i class="fas fa-chart-pie"></i> Durum Dağılımı</h3>
            </div>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- PROVIDER + TOP CUSTOMERS --}}
<div class="row">
    <div class="col-lg-6 d-flex">
        <div class="er-card flex-fill">
            <div class="er-card-header">
                <h3><i class="fas fa-fire"></i> En Çok Satan Hizmetler</h3>
            </div>
            @forelse($topServices as $s)
                @php
                    $pct = $totalRevenue > 0 ? round(($s->total / $totalRevenue) * 100, 1) : 0;
                    $isActivity = ($s->service_type === 'activity');
                    $icon = $isActivity ? 'fa-mountain-sun' : 'fa-shuttle-van';
                    $color = $isActivity ? '#f59e0b' : '#0ea5e9';
                @endphp
                <div class="er-provider-row">
                    <div class="er-provider-name">
                        <span><i class="fas {{ $icon }}" style="color:{{ $color }};margin-right:10px;"></i>{{ ucwords($s->service_name ?? 'Bilinmiyor') }}</span>
                        <span>{{ $currency }}{{ number_format($s->total, 2) }}</span>
                    </div>
                    <div class="er-progress"><div class="er-progress-bar" style="width: {{ $pct }}%;background: linear-gradient(90deg, {{ $color }}, var(--admin-primary));"></div></div>
                    <div class="er-provider-meta" style="margin-top:6px;">
                        <i class="fas fa-tag" style="font-size:10px;"></i> {{ $isActivity ? 'Aktivite' : 'Transfer' }}
                        · {{ $s->sales }} satış · %{{ $pct }} pay
                    </div>
                </div>
            @empty
                <p style="color:#94a3b8;text-align:center;padding:24px 0;">Henüz satış kaydı yok.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-6 d-flex">
        <div class="er-card flex-fill">
            <div class="er-card-header">
                <h3><i class="fas fa-bullseye"></i> Hedef Performansı</h3>
                <span style="font-size:11px;color:#94a3b8;">Hedef vs Gerçekleşen</span>
            </div>
            @foreach($goals as $g)
                @php
                    $pct = $g['target'] > 0 ? min(round(($g['actual'] / $g['target']) * 100, 1), 100) : 0;
                    $reached = $g['actual'] >= $g['target'];
                @endphp
                <div style="padding:14px 0;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:14px;font-weight:600;color:#0f172a;">
                            <i class="fas {{ $g['icon'] }}" style="color:{{ $g['color'] }};margin-right:8px;"></i>{{ $g['label'] }}
                        </span>
                        <span style="font-size:13px;color:#64748b;">
                            <strong style="color:{{ $reached ? '#10b981' : '#0f172a' }};">{{ $currency }}{{ number_format($g['actual'], 0) }}</strong>
                            / {{ $currency }}{{ number_format($g['target'], 0) }}
                        </span>
                    </div>
                    <div style="height:4px;background:#f1f5f9;border-radius:50px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg, {{ $g['color'] }}, {{ $reached ? '#10b981' : $g['color'] }});border-radius:50px;transition:width 0.6s ease;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:11px;color:#94a3b8;">
                        <span>%{{ $pct }} tamamlandı</span>
                        @if($reached)
                            <span style="color:#10b981;font-weight:700;"><i class="fas fa-check-circle"></i> Hedef aşıldı!</span>
                        @else
                            <span>Kalan: {{ $currency }}{{ number_format(max($g['target'] - $g['actual'], 0), 0) }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- RECENT TRANSACTIONS --}}
<div class="er-card">
    <div class="er-card-header">
        <h3><i class="fas fa-receipt"></i> Son İşlemler</h3>
    </div>
    <table class="er-table">
        <thead>
            <tr>
                <th>Sipariş No</th>
                <th>Müşteri</th>
                <th>Sağlayıcı</th>
                <th>Tutar</th>
                <th>Durum</th>
                <th>Tarih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTx as $tx)
                <tr>
                    <td><strong>{{ $tx->order_id ?? '#'.$tx->id }}</strong></td>
                    <td>
                        @if($tx->first_name)
                            {{ $tx->first_name }} {{ $tx->last_name }}<br>
                            <small style="color:#94a3b8;">{{ $tx->email }}</small>
                        @else
                            <em style="color:#94a3b8;">—</em>
                        @endif
                    </td>
                    <td>{{ ucfirst($tx->provider ?? '—') }}</td>
                    <td class="amount">{{ $currency }}{{ number_format($tx->amount, 2) }}</td>
                    <td><span class="er-status {{ $tx->status }}">{{ $tx->status }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($tx->created_at)->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:30px;">Henüz işlem yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    const dailyLabels  = @json($dailyLabels);
    const dailyData    = @json($dailyData);
    const monthlyLabels = @json($monthlyLabels);
    const monthlyData   = @json($monthlyData);
    const currency = @json($currency);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0,102,204,0.35)');
    gradient.addColorStop(1, 'rgba(0,102,204,0.02)');

    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Gelir',
                data: dailyData,
                borderColor: '#0066cc',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#0066cc',
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (c) => currency + c.parsed.y.toLocaleString('tr-TR', {minimumFractionDigits: 2})
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (v) => currency + v }
                }
            }
        }
    });

    document.querySelectorAll('.er-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.er-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const mode = this.dataset.chart;
            if (mode === 'daily') {
                revenueChart.data.labels = dailyLabels;
                revenueChart.data.datasets[0].data = dailyData;
            } else {
                revenueChart.data.labels = monthlyLabels;
                revenueChart.data.datasets[0].data = monthlyData;
            }
            revenueChart.update();
        });
    });

    // Status pie
    const sb = @json($statusBreakdown);
    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Başarılı', 'Bekleyen', 'Başarısız'],
            datasets: [{
                data: [sb.paid, sb.pending, sb.failed],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12, padding: 12, font: { size: 12 },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            const total = data.datasets[0].data.reduce((a,b) => a+b, 0);
                            return data.labels.map((label, i) => {
                                const val = data.datasets[0].data[i];
                                const pct = total > 0 ? ((val/total)*100).toFixed(1) : 0;
                                return {
                                    text: label + ' — %' + pct,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].backgroundColor[i],
                                    lineWidth: 0,
                                    index: i,
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            const total = c.dataset.data.reduce((a,b) => a+b, 0);
                            const pct = total > 0 ? ((c.parsed/total)*100).toFixed(1) : 0;
                            return c.label + ': ' + currency + c.parsed.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + ' (%' + pct + ')';
                        }
                    }
                }
            }
        }
    });
})();
</script>
@endsection
