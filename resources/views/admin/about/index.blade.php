@extends('layouts.admin')

@section('title', 'Hakkımızda')

@section('content')
<div class="cust-hero">
    <div class="cust-hero-left">
        <div class="ph-icon"><i class="fas fa-info-circle"></i></div>
        <div>
            <h1>Hakkımızda</h1>
            <div class="ph-sub">Sitedeki Hakkımızda sayfasında görünen içerikleri düzenle</div>
        </div>
    </div>
</div>

<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:var(--admin-radius);border:none;font-size:14px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-card">
        <div class="admin-table-header">
            <h5><i class="fas fa-info-circle" style="color:var(--admin-primary);margin-right:8px;"></i> Hakkımızda Sayfası</h5>
        </div>
        <div style="padding:24px;">
            @php
                $stats = $ayar && $ayar->about_stats ? json_decode($ayar->about_stats, true) : [];
                $features = $ayar && $ayar->about_features ? json_decode($ayar->about_features, true) : [];

                $defaultTitle   = 'Who We Are';
                $defaultText    = ($ayar->site_baslik ?? 'Marmaris Travel Center') . ' is a locally based travel company specialising in airport transfers and curated holiday experiences along Turkey\'s stunning Aegean and Mediterranean coast. From Marmaris and Fethiye to Oludeniz and beyond, we help travellers enjoy a smooth, comfortable, and memorable holiday from the moment they land.';
                $defaultMission = 'To make every journey effortless and every activity unforgettable. We focus on transparent pricing in British Pounds, reliable transfers with professional drivers, and handpicked excursions that show you the real beauty of the Turkish coast.';

                if (empty($stats) || empty($stats[0]['num'])) {
                    $stats = [
                        ['num' => '10+',  'label' => 'Years Experience', 'icon' => 'fas fa-calendar-check'],
                        ['num' => '15k+', 'label' => 'Happy Guests',     'icon' => 'fas fa-users'],
                        ['num' => '24/7', 'label' => 'Support',          'icon' => 'fas fa-headset'],
                        ['num' => '',     'label' => '',                 'icon' => ''],
                    ];
                }

                if (empty($features)) {
                    $features = [
                        'Local expertise: Our team lives and works in the region we serve.',
                        'English-speaking support: Friendly help before, during, and after your trip.',
                        'Fair, upfront pricing: No hidden fees, all prices in £.',
                        'Flexible booking: Easy online reservation with instant email confirmation.',
                        'Trusted drivers & partners: Safe, clean, and on time — every time.',
                    ];
                }
            @endphp

            <div class="mb-3">
                <label class="settings-label">Başlık</label>
                <input type="text" name="about_title" class="settings-input" value="{{ old('about_title', $ayar->about_title ?? $defaultTitle) }}" placeholder="Who We Are">
            </div>

            <div class="mb-3">
                <label class="settings-label">Tanıtım Metni</label>
                <textarea name="about_text" class="settings-input" rows="5" style="height:auto;" placeholder="Firmanız hakkında tanıtım metni...">{{ old('about_text', $ayar->about_text ?? $defaultText) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="settings-label">Misyon Metni</label>
                <textarea name="about_mission" class="settings-input" rows="3" style="height:auto;" placeholder="Misyonunuz nedir...">{{ old('about_mission', $ayar->about_mission ?? $defaultMission) }}</textarea>
            </div>

            @include('admin.partials.ceviri-kutulari', [
                'kayit'   => $ayar,
                'alanlar' => [
                    'about_title'   => ['etiket' => 'Hakkımızda Başlığı', 'tip' => 'input'],
                    'about_text'    => ['etiket' => 'Hakkımızda Metni',   'tip' => 'textarea'],
                    'about_mission' => ['etiket' => 'Misyon Metni',       'tip' => 'textarea'],
                    'about_features' => ['etiket' => 'Neden Bizi Seçmelisiniz (her satır bir madde)', 'tip' => 'textarea'],
                ],
            ])

<div class="mb-3">
                <label class="settings-label">Neden Bizi Seçmelisiniz (her satır bir madde)</label>
                <textarea name="about_features" class="settings-input" rows="6" style="height:auto;" placeholder="Local expertise: Our team lives and works in the region we serve.&#10;English-speaking support: Friendly help before, during, and after your trip.">{{ old('about_features', implode("\n", $features)) }}</textarea>
                <small style="color:var(--admin-text-light);font-size:12px;">Her satıra bir madde yazın. "Başlık: Açıklama" formatında olabilir.</small>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn-admin"><i class="fas fa-save"></i> Kaydet</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .settings-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--admin-dark);
        margin-bottom: 6px;
    }
    .settings-input {
        width: 100%;
        height: 46px;
        border: 2px solid var(--admin-border);
        border-radius: 8px;
        padding: 0 14px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        color: var(--admin-dark);
        transition: all 0.2s;
        background: #fff;
    }
    .settings-input:focus {
        border-color: var(--admin-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
    }
    textarea.settings-input {
        height: auto;
        padding: 12px 14px;
        resize: vertical;
    }
</style>
@endpush
