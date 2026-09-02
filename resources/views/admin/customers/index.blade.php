@extends('layouts.admin')

@section('title', 'Müşteriler')

@section('content')

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:12px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;line-height:1.5;"><i class="fas fa-ban"></i> {{ session('error') }}</div>
@endif

<style>
/* ══ HERO YENİ TASARIM ══ */
.ch2 {
    border-radius: 20px;
    padding: 0;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0f2440 0%, #1a3a7c 45%, #5b21b6 100%);
    box-shadow: 0 20px 60px rgba(15,36,64,0.28);
    display: flex; flex-direction: column;
}
.ch2-glow1 { position:absolute; top:-80px; right:-60px; width:260px; height:260px; border-radius:50%; background:radial-gradient(circle, rgba(99,102,241,0.35), transparent 65%); pointer-events:none; }
.ch2-glow2 { position:absolute; bottom:-100px; left:-40px; width:220px; height:220px; border-radius:50%; background:radial-gradient(circle, rgba(16,185,129,0.18), transparent 65%); pointer-events:none; }
.ch2-top {
    display: flex; align-items: center; gap: 0;
    padding: 20px 28px; position: relative; z-index: 2;
}
.ch2-icon {
    width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
    background: rgba(255,255,255,0.13); backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff; margin-right: 16px;
}
.ch2-title { flex: 1; }
.ch2-title h1 { font-size: 20px; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.3px; }
.ch2-title p  { font-size: 12px; color: rgba(255,255,255,0.6); margin: 3px 0 0; }
.ch2-stats {
    display: flex; gap: 6px; margin: 0 20px;
}
.ch2-stat {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 24px; border-radius: 14px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    text-decoration: none; color: #fff;
    transition: background 0.15s, transform 0.15s;
    cursor: pointer;
}
.ch2-stat:hover { background: rgba(255,255,255,0.17); transform: translateY(-1px); color: #fff; }
.ch2-stat.active { background: rgba(255,255,255,0.22); border-color: rgba(255,255,255,0.4); }
.ch2-stat-icon { width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size:18px; }
.ch2-stat-icon.t { background: rgba(14,165,233,0.3); color: #7dd3fc; }
.ch2-stat-icon.a { background: rgba(16,185,129,0.3); color: #6ee7b7; }
.ch2-stat-num  { font-size: 28px; font-weight: 800; line-height: 1; }
.ch2-stat-lbl  { font-size: 11px; opacity: 0.65; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.05em; }
.ch2-actions { display: flex; gap: 8px; margin-left: auto; flex-shrink: 0; }
.ch2-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600;
    padding: 9px 18px; border-radius: 10px; text-decoration: none;
    transition: transform 0.15s, opacity 0.15s;
    white-space: nowrap;
}
.ch2-btn:hover { transform: translateY(-1px); opacity: 0.9; }
.ch2-btn.primary { background: rgba(255,255,255,0.95); color: #1a3a7c; }
.ch2-btn.secondary { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); }

/* ── divider ── */
.ch2-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 0 28px; position:relative; z-index:2; }

/* ── tarih filtresi ── */
.ch2-filter {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 28px; position: relative; z-index: 2;
}
.ch2-filter-label { font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
.ch2-select {
    appearance: none;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 9px; padding: 7px 32px 7px 12px;
    font-size: 13px; font-weight: 600; color: #fff;
    font-family: 'Poppins', sans-serif;
    cursor: pointer; outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%23ffffff' opacity='.6' d='M5 7l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center; background-size: 18px;
    transition: background 0.15s;
}
.ch2-select:hover, .ch2-select:focus { background-color: rgba(255,255,255,0.17); }
.ch2-select option { background: #1e3a8a; color: #fff; }
.ch2-filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; padding: 7px 16px;
    border-radius: 9px; border: none; cursor: pointer;
    background: rgba(255,255,255,0.18); color: #fff;
    font-family: 'Poppins', sans-serif; transition: background 0.15s;
    text-decoration: none;
}
.ch2-filter-btn:hover { background: rgba(255,255,255,0.28); color: #fff; }
.ch2-filter-btn.apply { background: #4f46e5; }
.ch2-filter-btn.apply:hover { background: #4338ca; }
.ch2-active-filters { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.ch2-chip {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:700;
    padding:4px 10px; border-radius:20px;
    background: rgba(255,255,255,0.18); color:#fff;
    border: 1px solid rgba(255,255,255,0.25);
}
.ch2-chip a { color:rgba(255,255,255,0.7); text-decoration:none; margin-left:2px; }
.ch2-chip a:hover { color:#fff; }

/* ══ TABLO KARTI ══ */
.cust-card {
    background: #fff; border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    border: 1px solid #e8eef5; overflow: hidden;
}
.cust-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px; border-bottom: 1px solid #f1f5f9;
}
.cust-toolbar h5 {
    font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;
    display: flex; align-items: center; gap: 8px;
}
.cust-toolbar h5 i { color: var(--admin-primary); }
.filter-clear {
    display:inline-flex; align-items:center; gap:5px;
    font-size:12px; color:#64748b; text-decoration:none;
    padding:5px 10px; border-radius:8px; background:#f1f5f9;
    transition:background 0.15s;
}
.filter-clear:hover { background:#e2e8f0; color:#334155; }

.cust-tbl { width:100%; border-collapse:collapse; }
.cust-tbl thead tr { background:#f8fafc; border-bottom:2px solid #e8eef5; }
.cust-tbl thead th { padding:11px 16px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; white-space:nowrap; }
.cust-tbl tbody tr { border-bottom:1px solid #f1f5f9; transition:background 0.12s; }
.cust-tbl tbody tr:last-child { border-bottom:none; }
.cust-tbl tbody tr:hover { background:#f8faff; }
.cust-tbl td { padding:13px 16px; font-size:13px; color:#334155; vertical-align:middle; }

.cust-avatar { width:38px; height:38px; border-radius:11px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; flex-shrink:0; color:#fff; }
.cust-name-wrap { display:flex; align-items:center; gap:10px; }
.cust-name-wrap .nm { font-weight:700; color:#0f172a; font-size:13px; }
.cust-name-wrap .em { font-size:11px; color:#94a3b8; margin-top:1px; }
.cust-id { font-size:11px; font-weight:700; color:#64748b; background:#f1f5f9; border-radius:6px; padding:3px 7px; white-space:nowrap; }

.type-pill { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; white-space:nowrap; }
.type-pill.transfer { background:#dbeafe; color:#1d4ed8; }
.type-pill.activity  { background:#d1fae5; color:#065f46; }

.pay-pill { font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; white-space:nowrap; }
.pay-pill.paid   { background:#dcfce7; color:#16a34a; }
.pay-pill.unpaid { background:#fef3c7; color:#d97706; }

.btn-view { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:600; padding:6px 14px; border-radius:8px; background:linear-gradient(135deg, #0066cc, #6d28d9); color:#fff; text-decoration:none; transition:opacity 0.15s, transform 0.15s; white-space:nowrap; }
.btn-view:hover { opacity:0.88; transform:translateY(-1px); color:#fff; }

.empty-state { text-align:center; padding:60px 20px; color:#94a3b8; }
.empty-state i { font-size:40px; display:block; margin-bottom:14px; opacity:0.3; }
</style>

{{-- ══ HERO ══ --}}
<div class="ch2">
    <div class="ch2-glow1"></div>
    <div class="ch2-glow2"></div>

    <div class="ch2-top">
        <div class="ch2-icon">
            @if($type === 'transfer')<i class="fas fa-shuttle-van"></i>
            @elseif($type === 'activity')<i class="fas fa-mountain-sun"></i>
            @else<i class="fas fa-users"></i>
            @endif
        </div>
        <div class="ch2-title">
            <h1>@if($type === 'transfer') Transfer Müşterileri @elseif($type === 'activity') Aktivite Müşterileri @else Müşteriler @endif</h1>
            <p>@if($type === 'transfer') {{ $totalTransfer }} transfer rezervasyonu @elseif($type === 'activity') {{ $totalActivity }} aktivite rezervasyonu @else {{ $totalTransfer + $totalActivity }} toplam kayıt @endif</p>
        </div>
        <div class="ch2-stats">
            @if(!$type || $type === 'transfer')
            <a href="{{ route('admin.customers.index', array_filter(['type'=>'transfer','day'=>$day,'month'=>$month,'year'=>$year])) }}"
               class="ch2-stat {{ $type==='transfer' ? 'active' : '' }}">
                <div class="ch2-stat-icon t"><i class="fas fa-shuttle-van"></i></div>
                <div>
                    <div class="ch2-stat-num">{{ $totalTransfer }}</div>
                    <div class="ch2-stat-lbl">Transfer</div>
                </div>
            </a>
            @endif
            @if(!$type || $type === 'activity')
            <a href="{{ route('admin.customers.index', array_filter(['type'=>'activity','day'=>$day,'month'=>$month,'year'=>$year])) }}"
               class="ch2-stat {{ $type==='activity' ? 'active' : '' }}">
                <div class="ch2-stat-icon a"><i class="fas fa-mountain-sun"></i></div>
                <div>
                    <div class="ch2-stat-num">{{ $totalActivity }}</div>
                    <div class="ch2-stat-lbl">Aktivite</div>
                </div>
            </a>
            @endif
        </div>
        <div class="ch2-actions">
            <a href="{{ route('admin.customers.create') }}" class="ch2-btn primary"><i class="fas fa-user-plus"></i> Müşteri Ekle</a>
            <a href="{{ route('admin.customers.export', request()->only('type','day','month','year')) }}" class="ch2-btn secondary"><i class="fas fa-download"></i> CSV</a>
        </div>
    </div>

    <div class="ch2-divider"></div>

    {{-- Tarih filtresi --}}
    <form method="GET" action="{{ route('admin.customers.index') }}" class="ch2-filter">
        @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
        <span class="ch2-filter-label"><i class="fas fa-calendar-alt" style="margin-right:5px;"></i> Tarih</span>

        {{-- Hangi tarihe gore suzulecek: musterinin gelis tarihi mi, kayit tarihi mi --}}
        <select name="tarih" class="ch2-select" title="Hangi tarihe göre süzülsün">
            <option value="gelis" {{ ($tarih ?? 'gelis') !== 'kayit' ? 'selected' : '' }}>Geliş tarihi</option>
            <option value="kayit" {{ ($tarih ?? '') === 'kayit' ? 'selected' : '' }}>Kayıt tarihi</option>
        </select>

        <select name="day" class="ch2-select">
            <option value="">Gün</option>
            @for($d=1;$d<=31;$d++)
                <option value="{{ $d }}" {{ $day == $d ? 'selected' : '' }}>{{ str_pad($d,2,'0',STR_PAD_LEFT) }}</option>
            @endfor
        </select>

        <select name="month" class="ch2-select">
            <option value="">Ay</option>
            @foreach(['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'] as $i=>$m)
                <option value="{{ $i+1 }}" {{ $month == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>

        <select name="year" class="ch2-select">
            <option value="">Yıl</option>
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        <button type="submit" class="ch2-filter-btn apply"><i class="fas fa-search"></i> Filtrele</button>

        @if($day || $month || $year)
            <a href="{{ route('admin.customers.index', array_filter(['type'=>$type,'tarih'=>($tarih ?? null)])) }}" class="ch2-filter-btn">
                <i class="fas fa-times"></i> Temizle
            </a>
            @php
                // Secilen tarihi tek ve okunabilir bir ifadeye cevir: "9 Nisan 2026", "Nisan 2026", "2026"
                $__aylar = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
                $__parca = array_filter([
                    $day   ? (int) $day : null,
                    $month ? ($__aylar[$month - 1] ?? null) : null,
                    $year  ?: null,
                ]);
                $__tarihMetni = implode(' ', $__parca);
                $__tarihTuru  = ($tarih ?? 'gelis') === 'kayit' ? 'Kayıt' : 'Geliş';
            @endphp
            <div class="ch2-active-filters">
                <span class="ch2-chip">
                    <i class="fas fa-calendar-alt"></i> {{ $__tarihTuru }}: {{ $__tarihMetni }}
                </span>
            </div>
        @endif
    </form>
</div>

{{-- ══ TABLO ══ --}}
<div class="cust-card">
    <div class="cust-toolbar">
        <h5>
            <i class="fas fa-{{ $type==='transfer' ? 'shuttle-van' : ($type==='activity' ? 'mountain-sun' : 'list') }}"></i>
            @if($type === 'transfer') Transfer Müşterileri
            @elseif($type === 'activity') Aktivite Müşterileri
            @else Tüm Müşteriler
            @endif
            <span style="font-size:12px;font-weight:600;color:#94a3b8;margin-left:4px;">({{ $customers->total() }})</span>
        </h5>
        @if($type)
            <a href="{{ route('admin.customers.index', array_filter(['day'=>$day,'month'=>$month,'year'=>$year])) }}" class="filter-clear">
                <i class="fas fa-times"></i> Filtreyi kaldır
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="cust-tbl">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Müşteri</th>
                    <th>Telefon</th>
                    <th>Tür</th>
                    <th>Rezervasyon</th>
                    <th>Otel</th>
                    <th>Tarih</th>
                    <th>Harcama</th>
                    <th>Durum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                @php
                    $initials = strtoupper(substr($customer->first_name,0,1).substr($customer->last_name,0,1));
                    $colors = ['#6d28d9','#0369a1','#065f46','#9a3412','#1d4ed8','#7c3aed','#0891b2'];
                    $color  = $colors[$customer->id % count($colors)];
                @endphp
                <tr>
                    <td><span class="cust-id">#{{ str_pad($customer->id,5,'0',STR_PAD_LEFT) }}</span></td>
                    <td>
                        <div class="cust-name-wrap">
                            <div class="cust-avatar" style="background:{{ $color }}">{{ $initials }}</div>
                            <div>
                                <div class="nm">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                <div class="em">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <span class="type-pill {{ $customer->type ?? 'transfer' }}">
                            <i class="fas fa-{{ ($customer->type ?? 'transfer') === 'transfer' ? 'shuttle-van' : 'mountain-sun' }}"></i>
                            {{ ($customer->type ?? 'transfer') === 'transfer' ? 'Transfer' : 'Aktivite' }}
                        </span>
                    </td>
                    <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $customer->activity_name ?: ($customer->package ?: '—') }}
                    </td>
                    <td>{{ $customer->hotel_name ?: '—' }}</td>
                    <td style="white-space:nowrap;color:#64748b;">
                        {{ optional($customer->created_at)->format('d M Y') }}
                        <br><span style="font-size:11px;">{{ optional($customer->created_at)->format('H:i') }}</span>
                    </td>
                    <td style="white-space:nowrap;">
                        <strong style="color:#059669;font-size:14px;">£{{ number_format((float)($customer->total_spent??0),2) }}</strong>
                    </td>
                    <td>
                        <span class="pay-pill {{ $customer->payment_status==='paid' ? 'paid' : 'unpaid' }}">
                            {{ $customer->payment_status==='paid' ? 'Ödendi' : 'Bekliyor' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-view">
                            <i class="fas fa-eye"></i> İncele
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10">
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <p>@if($type==='transfer') Henüz transfer müşterisi yok. @elseif($type==='activity') Henüz aktivite müşterisi yok. @else Henüz müşteri yok. @endif</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($customers->hasPages())
    <div class="mt-3">{{ $customers->links() }}</div>
@endif
@endsection
