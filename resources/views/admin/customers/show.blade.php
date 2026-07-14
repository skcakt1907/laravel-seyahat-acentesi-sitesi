@extends('layouts.admin')

@section('title', $customer->first_name . ' ' . $customer->last_name)

@section('content')
<style>
    .crm-back { display:inline-flex; align-items:center; gap:6px; color:#64748b; font-size:13px; text-decoration:none; margin-bottom:14px; }
    .crm-back:hover { color: var(--admin-primary); }

    .crm-hero {
        background: linear-gradient(135deg, #0f2440 0%, #1e3a8a 50%, #6d28d9 100%);
        border-radius: 20px;
        padding: 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 20px 60px rgba(15,36,64,0.25);
    }
    .crm-hero::before {
        content: '';
        position: absolute;
        top: -100px; right: -100px;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(0,102,204,0.3), transparent 70%);
        border-radius: 50%;
    }
    .crm-hero::after {
        content: '';
        position: absolute;
        bottom: -120px; left: -60px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(16,185,129,0.22), transparent 70%);
        border-radius: 50%;
    }
    .crm-hero-inner { position: relative; z-index: 2; display: flex; gap: 24px; align-items: center; flex-wrap: wrap; }
    .crm-avatar {
        width: 96px; height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0099ff, #f59e0b);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 36px; font-weight: 800; color: #fff;
        box-shadow: 0 12px 30px rgba(0,102,204,0.45);
        flex-shrink: 0;
    }
    .crm-name { font-size: 32px; font-weight: 800; line-height: 1.2; }
    .crm-meta { display: flex; gap: 18px; flex-wrap: wrap; margin-top: 10px; font-size: 13px; opacity: 0.85; }
    .crm-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .crm-score-wrap { margin-left: auto; text-align: center; }
    .crm-score-circle {
        width: 92px; height: 92px;
        border-radius: 50%;
        background: conic-gradient(#10b981 0% {{ $score }}%, rgba(255,255,255,0.15) {{ $score }}% 100%);
        display: inline-flex; align-items: center; justify-content: center;
        position: relative;
    }
    .crm-score-inner {
        width: 70px; height: 70px;
        border-radius: 50%;
        background: #0f2440;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 800; color: #fff;
    }
    .crm-score-label { font-size: 11px; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 6px; display: block; }

    .crm-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .crm-kpi { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
    .crm-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; margin-bottom: 12px; }
    .crm-kpi.green .crm-kpi-icon { background: linear-gradient(135deg, #10b981, #059669); }
    .crm-kpi.blue  .crm-kpi-icon { background: linear-gradient(135deg, #0ea5e9, #0369a1); }
    .crm-kpi.orange .crm-kpi-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .crm-kpi.purple .crm-kpi-icon { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .crm-kpi-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; font-weight: 700; }
    .crm-kpi-value { font-size: 24px; font-weight: 800; color: #0f172a; margin-top: 4px; }

    .crm-card { background:#fff; border-radius:16px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e2e8f0; margin-bottom:24px; }
    .crm-card h3 { font-size:15px; font-weight:700; color:#0f172a; margin:0 0 16px; display:flex; align-items:center; gap:10px; }
    .crm-card h3 i { color: var(--admin-primary); }

    .crm-info-row { display:flex; padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:13px; }
    .crm-info-row:last-child { border-bottom:none; }
    .crm-info-row .lbl { width:130px; color:#64748b; font-weight:600; }
    .crm-info-row .val { flex:1; color:#0f172a; }

    .crm-table { width:100%; border-collapse:collapse; }
    .crm-table th { text-align:left; padding:8px 10px; font-size:11px; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; font-weight:700; border-bottom:2px solid #e2e8f0; }
    .crm-table td { padding:10px; font-size:13px; color:#334155; border-bottom:1px solid #f1f5f9; }
    .crm-status { display:inline-block; padding:2px 8px; border-radius:50px; font-size:10px; font-weight:700; text-transform:uppercase; }
    .crm-status.paid { background:#dcfce7; color:#166534; }
    .crm-status.pending { background:#fef3c7; color:#92400e; }
    .crm-status.failed { background:#fee2e2; color:#991b1b; }

    .crm-timeline { position:relative; padding-left:30px; }
    .crm-timeline::before { content:''; position:absolute; left:11px; top:6px; bottom:6px; width:2px; background:#e2e8f0; }
    .crm-tl-item { position:relative; padding-bottom:18px; }
    .crm-tl-item:last-child { padding-bottom:0; }
    .crm-tl-icon {
        position:absolute; left:-30px; top:0;
        width:24px; height:24px; border-radius:50%;
        background:#fff; border:2px solid currentColor;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:10px;
    }
    .crm-tl-title { font-size:13px; font-weight:700; color:#0f172a; }
    .crm-tl-desc  { font-size:12px; color:#64748b; margin-top:2px; }
    .crm-tl-date  { font-size:11px; color:#94a3b8; margin-top:3px; }

    /* Tags */
    .crm-tags { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
    .crm-tag { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:50px; font-size:11px; font-weight:700; color:#fff; }

    /* Quick actions bar */
    .crm-actions { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:22px; }
    .crm-act-btn {
        display:inline-flex; align-items:center; gap:8px;
        padding:10px 18px; border-radius:10px; font-size:13px; font-weight:700;
        text-decoration:none; cursor:pointer; border:none; transition:all 0.2s;
        font-family:'Poppins',sans-serif;
    }
    .crm-act-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,0.12); text-decoration:none; }
    .crm-act-btn.wa { background:#25d366; color:#fff; }
    .crm-act-btn.email { background:#0ea5e9; color:#fff; }
    .crm-act-btn.call { background:#8b5cf6; color:#fff; }
    .crm-act-btn.pay { background:#10b981; color:#fff; }
    .crm-act-btn.del { background:#ef4444; color:#fff; }

    /* Note editor */
    .crm-note-box { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:16px; }
    .crm-note-box textarea {
        width:100%; min-height:90px; border:none; background:transparent; resize:vertical;
        font-family:'Poppins',sans-serif; font-size:13px; color:#78350f; outline:none;
    }
    .crm-note-box textarea::placeholder { color:#d97706; opacity:0.7; }

    @media (max-width:991px){ .crm-kpi-grid{grid-template-columns:repeat(2,1fr);} }
    @media (max-width:576px){ .crm-kpi-grid{grid-template-columns:1fr;} .crm-name{font-size:24px;} .crm-actions{flex-direction:column;} .crm-act-btn{justify-content:center;} }
</style>

<a href="{{ route('admin.customers.index') }}" class="crm-back"><i class="fas fa-arrow-left"></i> Müşterilere geri dön</a>

{{-- HERO --}}
<div class="crm-hero">
    <div class="crm-hero-inner">
        <div class="crm-avatar">{{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}</div>
        <div>
            <div class="crm-name">{{ $customer->first_name }} {{ $customer->last_name }}</div>
            <div style="font-size:13px;opacity:0.8;margin-top:4px;">Müşteri #TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }} · Üyelik {{ optional($customer->created_at)->format('d M Y') }}</div>
            <div class="crm-meta">
                <span><i class="fas fa-envelope"></i> {{ $customer->email }}</span>
                <span><i class="fas fa-phone"></i> {{ $customer->phone }}</span>
                @if($customer->hotel_name)<span><i class="fas fa-hotel"></i> {{ $customer->hotel_name }}</span>@endif
            </div>
            <div class="crm-tags">
                @foreach($tags as $tg)
                    <span class="crm-tag" style="background:{{ $tg['color'] }};"><i class="fas {{ $tg['icon'] }}"></i> {{ $tg['label'] }}</span>
                @endforeach
            </div>
        </div>
        <div class="crm-score-wrap">
            <div class="crm-score-circle">
                <div class="crm-score-inner">{{ $score }}</div>
            </div>
            <span class="crm-score-label">Sadakat Skoru</span>
        </div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="crm-actions">
    @php
        $waPhone = preg_replace('/[^0-9]/', '', $customer->phone);
        $bookingId = '#TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT);
        $serviceName = $customer->activity_name ?: $customer->package;
        $template = $ayar->whatsapp_template ?? "Hello {first_name},\n\nThis is Marmaris Travel Center regarding your booking {booking_id}.\n\n";
        $vars = [
            '{first_name}'     => $customer->first_name,
            '{last_name}'      => $customer->last_name,
            '{full_name}'      => trim($customer->first_name . ' ' . $customer->last_name),
            '{booking_id}'     => $bookingId,
            '{service}'        => $serviceName ?: '-',
            '{arrival_date}'   => $customer->arrival_date ? \Carbon\Carbon::parse($customer->arrival_date)->format('d M Y') : '-',
            '{arrival_time}'   => $customer->arrival_time ?? '',
            '{departure_date}' => $customer->departure_date ? \Carbon\Carbon::parse($customer->departure_date)->format('d M Y') : '-',
            '{departure_time}' => $customer->departure_time ?? '',
            '{hotel}'          => $customer->hotel_name ?: '-',
            '{phone}'          => $customer->phone,
            '{email}'          => $customer->email,
        ];
        $waMsg = urlencode(strtr($template, $vars));
    @endphp
    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="crm-act-btn wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
    <a href="mailto:{{ $customer->email }}" class="crm-act-btn email"><i class="fas fa-envelope"></i> E-posta Gönder</a>
    @if($customer->payment_status !== 'paid')
        <form action="{{ route('admin.customers.markPaid', $customer->id) }}" method="POST" style="display:inline-flex;gap:6px;align-items:center;" onsubmit="return confirm('Bu rezervasyonu ödendi olarak işaretle?');">
            @csrf
            <input type="number" step="0.01" name="amount" placeholder="£ tutar" style="width:90px;padding:9px 10px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;">
            <button type="submit" class="crm-act-btn pay"><i class="fas fa-coins"></i> Ödendi İşaretle</button>
        </form>
    @else
        <span class="crm-act-btn" style="background:#dcfce7;color:#166534;cursor:default;"><i class="fas fa-check-circle"></i> Ödeme Tamamlandı</span>
    @endif

    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="margin-left:auto;" onsubmit="return confirm('Bu müşteriyi ve tüm ödeme kayıtlarını silmek istediğine emin misin?');">
        @csrf @method('DELETE')
        <button type="submit" class="crm-act-btn del"><i class="fas fa-trash"></i> Müşteriyi Sil</button>
    </form>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- KPI --}}
<div class="crm-kpi-grid">
    <div class="crm-kpi green">
        <div class="crm-kpi-icon"><i class="fas fa-coins"></i></div>
        <div class="crm-kpi-label">Toplam Harcama</div>
        <div class="crm-kpi-value">£{{ number_format($totalSpent, 2) }}</div>
    </div>
    <div class="crm-kpi blue">
        <div class="crm-kpi-icon"><i class="fas fa-receipt"></i></div>
        <div class="crm-kpi-label">Başarılı Ödeme</div>
        <div class="crm-kpi-value">{{ $paidCount }}</div>
    </div>
    <div class="crm-kpi orange">
        <div class="crm-kpi-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="crm-kpi-label">Toplam Rezervasyon</div>
        <div class="crm-kpi-value">{{ $allBookings->count() }}</div>
    </div>
    <div class="crm-kpi purple">
        <div class="crm-kpi-icon"><i class="fas fa-comment-dots"></i></div>
        <div class="crm-kpi-label">Yorum / Mesaj</div>
        <div class="crm-kpi-value">{{ $reviews->count() + $messages->count() }}</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        {{-- Customer Info --}}
        <div class="crm-card">
            <h3><i class="fas fa-id-card"></i> Müşteri Bilgileri</h3>
            <div class="crm-info-row"><div class="lbl">Ad Soyad</div><div class="val">{{ $customer->first_name }} {{ $customer->last_name }}</div></div>
            <div class="crm-info-row"><div class="lbl">E-posta</div><div class="val">{{ $customer->email }}</div></div>
            <div class="crm-info-row"><div class="lbl">Telefon</div><div class="val">{{ $customer->phone }}</div></div>
            <div class="crm-info-row"><div class="lbl">Tür</div><div class="val">{{ ucfirst($customer->type ?? '—') }}</div></div>
            @if($customer->hotel_name)<div class="crm-info-row"><div class="lbl">Otel</div><div class="val">{{ $customer->hotel_name }}</div></div>@endif
            @if($customer->adult_count)<div class="crm-info-row"><div class="lbl">Yetişkin</div><div class="val">{{ $customer->adult_count }} kişi</div></div>@endif
            @if($customer->child_count)<div class="crm-info-row"><div class="lbl">Çocuk</div><div class="val">{{ $customer->child_count }} kişi</div></div>@endif
            @if($customer->adult_names)<div class="crm-info-row"><div class="lbl">Tüm Yolcular</div><div class="val">{{ $customer->adult_names }}</div></div>@endif
            @if($customer->child_names)<div class="crm-info-row"><div class="lbl">Çocuk İsimleri</div><div class="val">{{ $customer->child_names }}</div></div>@endif
            @if($customer->arrival_date)<div class="crm-info-row"><div class="lbl">Geliş</div><div class="val">{{ \Carbon\Carbon::parse($customer->arrival_date)->format('d M Y') }} {{ $customer->arrival_time }}</div></div>@endif
            @if($customer->departure_date)<div class="crm-info-row"><div class="lbl">Gidiş</div><div class="val">{{ \Carbon\Carbon::parse($customer->departure_date)->format('d M Y') }} {{ $customer->departure_time }}</div></div>@endif
            @if($customer->notes)<div class="crm-info-row"><div class="lbl">Notlar</div><div class="val">{{ $customer->notes }}</div></div>@endif
        </div>

        {{-- Internal CRM Note --}}
        <div class="crm-card">
            <h3><i class="fas fa-pen-to-square"></i> Dahili Not</h3>
            <form action="{{ route('admin.customers.updateNote', $customer->id) }}" method="POST">
                @csrf
                <div class="crm-note-box">
                    <textarea name="crm_note" placeholder="Bu müşteri hakkında özel notlar... (sadece sen görürsün)">{{ $customer->crm_note }}</textarea>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary" style="margin-top:10px;font-size:12px;padding:7px 14px;">
                    <i class="fas fa-save"></i> Notu Kaydet
                </button>
            </form>
        </div>

        {{-- Timeline --}}
        <div class="crm-card">
            <h3><i class="fas fa-stream"></i> Aktivite Akışı</h3>
            @if($timeline->isEmpty())
                <p style="color:#94a3b8;text-align:center;padding:20px 0;">Henüz aktivite yok.</p>
            @else
                <div class="crm-timeline">
                    @foreach($timeline as $t)
                        <div class="crm-tl-item" style="color:{{ $t['color'] }};">
                            <div class="crm-tl-icon"><i class="fas {{ $t['icon'] }}"></i></div>
                            <div class="crm-tl-title">{{ $t['title'] }}</div>
                            <div class="crm-tl-desc">{{ $t['desc'] }}</div>
                            <div class="crm-tl-date">{{ \Carbon\Carbon::parse($t['date'])->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Bookings: Transfers --}}
        <div class="crm-card">
            <h3><i class="fas fa-shuttle-van"></i> Transfer Rezervasyonları ({{ $transferBookings->count() }})</h3>
            @if($transferBookings->isEmpty())
                <p style="color:#94a3b8;text-align:center;padding:20px 0;">Transfer rezervasyonu yok.</p>
            @else
                <table class="crm-table">
                    <thead><tr><th>ID</th><th>Rota</th><th>Otel</th><th>Tarih</th><th>Yetişkin/Çocuk</th><th>Tutar</th></tr></thead>
                    <tbody>
                    @foreach($transferBookings as $b)
                        @php
                            $pax = max(1, (int)($b->adult_count ?? 0) + (int)($b->child_count ?? 0));
                            $tRec = \DB::table('transfers')->where('title', $b->package)->first();
                            $tPrice = 0;
                            if ($tRec) {
                                if ($pax <= 4)       $tPrice = (float)($tRec->price_1_4 ?? 0);
                                elseif ($pax <= 6)   $tPrice = (float)($tRec->price_5_6 ?? 0);
                                elseif ($pax <= 8)   $tPrice = (float)($tRec->price_7_8 ?? 0);
                                else                 $tPrice = (float)($tRec->price_9_14 ?? 0);
                            }
                        @endphp
                        <tr>
                            <td>#TCM{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $b->package }}</td>
                            <td>{{ $b->hotel_name ?? '—' }}</td>
                            <td>{{ optional($b->created_at)->format('d M Y') }}</td>
                            <td>{{ $b->adult_count ?? 0 }}/{{ $b->child_count ?? 0 }}</td>
                            <td><strong>£{{ number_format($tPrice, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Bookings: Activities --}}
        <div class="crm-card">
            <h3><i class="fas fa-mountain-sun"></i> Aktivite Rezervasyonları ({{ $activityBookings->count() }})</h3>
            @if($activityBookings->isEmpty())
                <p style="color:#94a3b8;text-align:center;padding:20px 0;">Aktivite rezervasyonu yok.</p>
            @else
                <table class="crm-table">
                    <thead><tr><th>ID</th><th>Aktivite</th><th>Otel</th><th>Kişi</th><th>Tarih</th><th>Tutar</th><th>Not</th></tr></thead>
                    <tbody>
                    @foreach($activityBookings as $b)
                        @php
                            $pax = max(1, (int)($b->adult_count ?? 0) + (int)($b->child_count ?? 0));
                            $aRec = \DB::table('activities')->where('slug', $b->package)->orWhere('title', $b->activity_name)->first();
                            $aPrice = (float)($aRec->price ?? 0) * $pax;
                        @endphp
                        <tr>
                            <td>#TCM{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $b->activity_name ?: $b->package }}</td>
                            <td>{{ $b->hotel_name ?? '—' }}</td>
                            <td>{{ ($b->adult_count ?? 0) }} Y / {{ ($b->child_count ?? 0) }} Ç</td>
                            <td>{{ $b->arrival_date ? \Carbon\Carbon::parse($b->arrival_date)->format('d M Y') : optional($b->created_at)->format('d M Y') }}</td>
                            <td><strong>£{{ number_format($aPrice, 2) }}</strong></td>
                            <td>{{ $b->notes ? \Str::limit($b->notes, 40) : '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Payments --}}
        <div class="crm-card">
            <h3><i class="fas fa-credit-card"></i> Ödeme Geçmişi ({{ $payments->count() }})</h3>
            @if($payments->isEmpty())
                <p style="color:#94a3b8;text-align:center;padding:20px 0;">Henüz ödeme yok.</p>
            @else
                <table class="crm-table">
                    <thead><tr><th>Sipariş No</th><th>Tutar</th><th>Sağlayıcı</th><th>Durum</th><th>Tarih</th></tr></thead>
                    <tbody>
                    @foreach($payments as $p)
                        <tr>
                            <td>{{ $p->order_id ?? '#'.$p->id }}</td>
                            <td><strong>£{{ number_format($p->amount, 2) }}</strong></td>
                            <td>{{ ucfirst($p->provider ?? '—') }}</td>
                            <td><span class="crm-status {{ $p->status }}">{{ $p->status }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
