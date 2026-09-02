@extends('layouts.admin')

@section('title', 'Müşteri Ekle')

@section('content')
<style>
    .ce-card { background:#fff; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e2e8f0; max-width:880px; }
    .ce-card h2 { font-size:20px; font-weight:800; margin:0 0 6px; color:#0f172a; }
    .ce-card .sub { font-size:13px; color:#64748b; margin-bottom:24px; }
    .ce-section { font-size:13px; font-weight:700; color:#0f172a; margin:24px 0 12px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; display:flex; align-items:center; gap:8px; }
    .ce-section i { color:var(--admin-primary); }
    .ce-section:first-of-type { margin-top:0; }
    .ce-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
    .ce-row.three { grid-template-columns:1fr 1fr 1fr; }
    .ce-field label { display:block; font-size:12px; font-weight:600; color:#334155; margin-bottom:4px; }
    .ce-field input, .ce-field select, .ce-field textarea {
        width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:14px; font-family:'Poppins',sans-serif; color:#0f172a; background:#fff;
    }
    .ce-field input:focus, .ce-field select:focus, .ce-field textarea:focus {
        outline:none; border-color:var(--admin-primary); box-shadow:0 0 0 3px rgba(0,102,204,0.1);
    }
    .ce-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9; }
    @media (max-width:768px) { .ce-row, .ce-row.three { grid-template-columns:1fr; } }
</style>

<a href="{{ route('admin.customers.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:#64748b;font-size:13px;text-decoration:none;margin-bottom:14px;">
    <i class="fas fa-arrow-left"></i> Müşterilere geri dön
</a>

<div class="ce-card">
    <h2><i class="fas fa-user-plus" style="color:var(--admin-primary);margin-right:8px;"></i> Yeni Müşteri Ekle</h2>
    <p class="sub">Manuel olarak rezervasyon kaydet (telefon ile gelen müşteriler için).</p>

    @if($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13px;">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf

        <div class="ce-section"><i class="fas fa-user"></i> Kişisel Bilgiler</div>
        <div class="ce-row">
            <div class="ce-field"><label>Ad *</label><input type="text" name="first_name" value="{{ old('first_name') }}" required></div>
            <div class="ce-field"><label>Soyad *</label><input type="text" name="last_name" value="{{ old('last_name') }}" required></div>
        </div>
        <div class="ce-row">
            <div class="ce-field"><label>E-posta *</label><input type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="ce-field"><label>Telefon *</label><input type="text" name="phone" value="{{ old('phone') }}" required></div>
        </div>

        <div class="ce-section"><i class="fas fa-bookmark"></i> Rezervasyon Türü</div>
        <div class="ce-row">
            <div class="ce-field">
                <label>Tür *</label>
                <select name="type" id="typeSelect" required>
                    <option value="transfer" {{ old('type') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="activity" {{ old('type') === 'activity' ? 'selected' : '' }}>Aktivite</option>
                </select>
            </div>
            <div class="ce-field" id="packageWrap">
                <label>Transfer Rotası</label>
                <select name="package">
                    <option value="">— Seçin —</option>
                    @foreach($transfers as $t)
                        @php
                            $tiers = array_filter([(float)$t->price_1_4, (float)$t->price_5_6, (float)$t->price_7_8, (float)$t->price_9_14]);
                            $minP = $tiers ? min($tiers) : (float)$t->price;
                            $maxP = $tiers ? max($tiers) : (float)$t->price;
                            $label = $minP == $maxP ? '£'.number_format($minP,2) : '£'.number_format($minP,2).'–£'.number_format($maxP,2);
                        @endphp
                        <option value="{{ $t->title }}" {{ old('package') === $t->title ? 'selected' : '' }}>{{ $t->title }} ({{ $label }})</option>
                    @endforeach
                </select>
            </div>
            <div class="ce-field" id="activityWrap" style="display:none;">
                <label>Aktivite</label>
                <select name="activity_name">
                    <option value="">— Seçin —</option>
                    @foreach($activities as $a)<option value="{{ $a->title }}" data-slug="{{ $a->slug }}">{{ $a->title }} (£{{ $a->price }})</option>@endforeach
                </select>
            </div>
        </div>

        <div class="ce-section"><i class="fas fa-hotel"></i> Konaklama & Yolcu</div>
        <div class="ce-row three">
            <div class="ce-field"><label>Otel</label><input type="text" name="hotel_name" value="{{ old('hotel_name') }}"></div>
            <div class="ce-field"><label>Yetişkin</label><input type="number" name="adult_count" value="{{ old('adult_count', 1) }}" min="0"></div>
            <div class="ce-field"><label>Çocuk</label><input type="number" name="child_count" value="{{ old('child_count', 0) }}" min="0"></div>
        </div>

        <div class="ce-section"><i class="fas fa-plane-arrival"></i> Geliş Bilgileri</div>
        <div class="ce-row">
            <div class="ce-field"><label>Geliş Tarihi</label><input type="date" name="arrival_date" value="{{ old('arrival_date') }}"></div>
            <div class="ce-field"><label>Geliş Saati</label><input type="time" name="arrival_time" value="{{ old('arrival_time') }}"></div>
        </div>

        <div class="ce-section"><i class="fas fa-plane-departure"></i> Gidiş Bilgileri</div>
        <div class="ce-row">
            <div class="ce-field"><label>Gidiş Tarihi</label><input type="date" name="departure_date" value="{{ old('departure_date') }}"></div>
            <div class="ce-field"><label>Gidiş Saati</label><input type="time" name="departure_time" value="{{ old('departure_time') }}"></div>
        </div>

        <div class="ce-section"><i class="fas fa-id-card"></i> Ek Bilgiler</div>
        <div class="ce-row">
            <div class="ce-field">
                <label>Doğum Tarihi</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}">
            </div>
            <div class="ce-field">
                <label>Kayıt Tarihi <span style="color:#94a3b8;font-weight:400;">(boş bırakılırsa bugün)</span></label>
                <input type="date" name="registered_at" value="{{ old('registered_at', date('Y-m-d')) }}">
            </div>
        </div>

        <div class="ce-section"><i class="fas fa-sticky-note"></i> Notlar</div>
        <div class="ce-field"><textarea name="notes" rows="3" placeholder="Özel istekler, notlar...">{{ old('notes') }}</textarea></div>

        <div class="ce-actions">
            <a href="{{ route('admin.customers.index') }}" class="btn-admin" style="background:#f1f5f9;color:#334155;">İptal</a>
            <button type="submit" class="btn-admin btn-admin-primary"><i class="fas fa-check"></i> Müşteriyi Kaydet</button>
        </div>
    </form>
</div>

<script>
(function(){
    var sel = document.getElementById('typeSelect');
    var pkg = document.getElementById('packageWrap');
    var act = document.getElementById('activityWrap');
    function toggle() {
        if (sel.value === 'activity') { pkg.style.display='none'; act.style.display='block'; }
        else { pkg.style.display='block'; act.style.display='none'; }
    }
    sel.addEventListener('change', toggle);
    toggle();
})();
</script>
@include('partials.intl-tel')
@endsection
