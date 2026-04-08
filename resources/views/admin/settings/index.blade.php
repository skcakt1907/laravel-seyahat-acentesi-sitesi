@extends('layouts.admin')

@section('title', 'Site Ayarları')

@section('content')
<div class="admin-page-hero">
    <div class="admin-page-hero-inner">
        <div>
            <h1><span class="ph-icon"><i class="fas fa-cog"></i></span> Site Ayarları</h1>
            <div class="ph-sub">Genel bilgiler, iletişim, renkler, ödeme sistemi ve daha fazlası</div>
        </div>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius:var(--admin-radius);border:none;font-size:14px;">
            @foreach($errors->all() as $error)
                <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row">
        {{-- General Info --}}
        <div class="col-lg-6 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-globe" style="color:var(--admin-primary);margin-right:8px;"></i> Genel Bilgiler</h5>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="settings-label">Site Adı *</label>
                        <input type="text" name="site_baslik" class="settings-input" value="{{ old('site_baslik', $ayar->site_baslik ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Firma Adı</label>
                        <input type="text" name="firma_adi" class="settings-input" value="{{ old('firma_adi', $ayar->firma_adi ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Site Açıklaması (SEO)</label>
                        <textarea name="site_desc" class="settings-input" rows="3" style="height:auto;">{{ old('site_desc', $ayar->site_desc ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Telif Hakkı Metni</label>
                        <input type="text" name="copyright" class="settings-input" value="{{ old('copyright', $ayar->copyright ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Google Analytics ID</label>
                        <input type="text" name="google_analytics" class="settings-input" value="{{ old('google_analytics', $ayar->google_analytics ?? '') }}" placeholder="G-XXXXXXXXXX">
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="col-lg-6 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-phone-alt" style="color:var(--admin-primary);margin-right:8px;"></i> İletişim Bilgileri</h5>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="settings-label">Telefon Numarası</label>
                        <input type="text" name="firma_telefon" class="settings-input" value="{{ old('firma_telefon', $ayar->firma_telefon ?? '') }}" placeholder="+90 555 555 5555">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">E-posta Adresi</label>
                        <input type="email" name="firma_email" class="settings-input" value="{{ old('firma_email', $ayar->firma_email ?? '') }}" placeholder="info@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Adres</label>
                        <textarea name="firma_adres" class="settings-input" rows="2" style="height:auto;">{{ old('firma_adres', $ayar->firma_adres ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">WhatsApp Numarası (ülke koduyla)</label>
                        <input type="text" name="whatsapp" class="settings-input" value="{{ old('whatsapp', $ayar->whatsapp ?? '') }}" placeholder="905555555555">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="settings-label">WhatsApp Mesaj Şablonu (CRM)</label>
                    <textarea name="whatsapp_template" class="settings-input" rows="6" placeholder="Müşteriye CRM'den WhatsApp gönderirken kullanılacak şablon">{{ old('whatsapp_template', $ayar->whatsapp_template ?? "Hello {first_name},\n\nThis is Marmaris Travel Center regarding your booking {booking_id}.\nService: {service}\nArrival: {arrival_date} {arrival_time}\nHotel: {hotel}\n\n") }}</textarea>
                    <small style="color:#64748b;font-size:12px;display:block;margin-top:6px;">
                        Kullanılabilir değişkenler:
                        <code>{first_name}</code>, <code>{last_name}</code>, <code>{full_name}</code>,
                        <code>{booking_id}</code>, <code>{service}</code>,
                        <code>{arrival_date}</code>, <code>{arrival_time}</code>,
                        <code>{departure_date}</code>, <code>{departure_time}</code>,
                        <code>{hotel}</code>, <code>{phone}</code>, <code>{email}</code>
                    </small>
                </div>
            </div>
        </div>

        {{-- Theme Colors --}}
        <div class="col-lg-6 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-palette" style="color:var(--admin-primary);margin-right:8px;"></i> Tema Renkleri</h5>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="settings-label">Ana Renk (butonlar, linkler)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="renk1" id="renk1" value="{{ old('renk1', $ayar->renk1 ?? '#0066cc') }}" style="width:50px;height:42px;border:2px solid var(--admin-border);border-radius:8px;cursor:pointer;padding:2px;">
                            <input type="text" id="renk1_text" class="settings-input mb-0" value="{{ old('renk1', $ayar->renk1 ?? '#0066cc') }}" style="flex:1;" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">İkincil Renk (vurgular, CTA)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="renk2" id="renk2" value="{{ old('renk2', $ayar->renk2 ?? '#ff6b00') }}" style="width:50px;height:42px;border:2px solid var(--admin-border);border-radius:8px;cursor:pointer;padding:2px;">
                            <input type="text" id="renk2_text" class="settings-input mb-0" value="{{ old('renk2', $ayar->renk2 ?? '#ff6b00') }}" style="flex:1;" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="settings-label">Koyu Renk (header, footer, metin)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="renk3" id="renk3" value="{{ old('renk3', $ayar->renk3 ?? '#0b1d33') }}" style="width:50px;height:42px;border:2px solid var(--admin-border);border-radius:8px;cursor:pointer;padding:2px;">
                            <input type="text" id="renk3_text" class="settings-input mb-0" value="{{ old('renk3', $ayar->renk3 ?? '#0b1d33') }}" style="flex:1;" readonly>
                        </div>
                    </div>
                    <div class="mt-3 p-3" id="colorPreview" style="border-radius:10px;border:2px solid var(--admin-border);">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <div id="prevPrimary" style="width:36px;height:36px;border-radius:8px;"></div>
                            <div id="prevSecondary" style="width:36px;height:36px;border-radius:8px;"></div>
                            <div id="prevDark" style="width:36px;height:36px;border-radius:8px;"></div>
                            <span style="font-size:13px;color:var(--admin-text-light);margin-left:8px;">Canlı Önizleme</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="col-lg-6 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-share-alt" style="color:var(--admin-primary);margin-right:8px;"></i> Sosyal Medya</h5>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="settings-label"><i class="fab fa-facebook" style="color:#1877f2;"></i> Facebook URL</label>
                        <input type="text" name="facebook" class="settings-input" value="{{ old('facebook', $ayar->facebook ?? '') }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label"><i class="fab fa-instagram" style="color:#e4405f;"></i> Instagram URL</label>
                        <input type="text" name="instagram" class="settings-input" value="{{ old('instagram', $ayar->instagram ?? '') }}" placeholder="https://instagram.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="settings-label"><i class="fab fa-tiktok" style="color:#000;"></i> TikTok URL</label>
                        <input type="text" name="twitter" class="settings-input" value="{{ old('twitter', $ayar->twitter ?? '') }}" placeholder="https://tiktok.com/@...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Logo & Favicon --}}
        <div class="col-12 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-image" style="color:var(--admin-primary);margin-right:8px;"></i> Logo ve Favicon</h5>
                </div>
                <div style="padding:24px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="settings-label">Site Logosu</label>
                            @if(!empty($ayar->firma_logo))
                                <div class="mb-2">
                                    <img src="{{ asset('tema/uploads/' . $ayar->firma_logo) }}" style="max-height:60px;border-radius:8px;">
                                </div>
                            @endif
                            <input type="file" name="firma_logo" class="form-control-file" accept="image/*">
                            <small style="color:var(--admin-text-light);font-size:12px;">Önerilen: Şeffaf arka planlı PNG</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="settings-label">Favicon</label>
                            @if(!empty($ayar->favicon))
                                <div class="mb-2">
                                    <img src="{{ asset('tema/uploads/' . $ayar->favicon) }}" style="max-height:32px;">
                                </div>
                            @endif
                            <input type="file" name="favicon" class="form-control-file" accept="image/*">
                            <small style="color:var(--admin-text-light);font-size:12px;">Önerilen: 32x32px ICO veya PNG</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Payment Gateway (Sanal POS) --}}
        <div class="col-12 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-credit-card" style="color:var(--admin-primary);margin-right:8px;"></i> Ödeme Sistemi (Sanal POS)</h5>
                </div>
                <div style="padding:24px;">
                    @php
                        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : [];
                    @endphp

                    <div class="mb-3">
                        <label class="settings-label">Ödeme Durumu</label>
                        <div class="d-flex align-items-center gap-2">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;font-weight:500;">
                                <input type="checkbox" name="odeme_aktif" value="1" {{ !empty($odemeAyarlari['aktif']) ? 'checked' : '' }}
                                    style="width:20px;height:20px;accent-color:var(--admin-primary);cursor:pointer;">
                                Online Ödemeyi Etkinleştir
                            </label>
                        </div>
                        <small style="color:var(--admin-text-light);font-size:12px;">Etkinleştirildiğinde, müşteriler rezervasyon sonrası ödeme formu görecek.</small>
                    </div>

                    <input type="hidden" name="odeme_provider" value="garanti">

                    {{-- Garanti Fields (tek desteklenen sağlayıcı) --}}
                    <div id="garantiFields">
                        <div class="mb-3">
                            <label class="settings-label">Terminal ID</label>
                            <input type="text" name="garanti_terminal_id" class="settings-input" value="{{ $odemeAyarlari['garanti_terminal_id'] ?? '' }}" placeholder="Terminal ID">
                        </div>
                        <div class="mb-3">
                            <label class="settings-label">Merchant ID</label>
                            <input type="text" name="garanti_merchant_id" class="settings-input" value="{{ $odemeAyarlari['garanti_merchant_id'] ?? '' }}" placeholder="Merchant ID">
                        </div>
                        <div class="mb-3">
                            <label class="settings-label">Store Key (3D Secure)</label>
                            <input type="text" name="garanti_store_key" class="settings-input" value="{{ $odemeAyarlari['garanti_store_key'] ?? '' }}" placeholder="3D Secure Store Key">
                        </div>
                        <div class="mb-3">
                            <label class="settings-label">Provision Password</label>
                            <input type="text" name="garanti_provision_password" class="settings-input" value="{{ $odemeAyarlari['garanti_provision_password'] ?? '' }}" placeholder="Provision Password">
                        </div>
                        <div class="mb-3">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;font-weight:500;">
                                <input type="checkbox" name="garanti_test_mode" value="1" {{ !empty($odemeAyarlari['garanti_test_mode']) ? 'checked' : '' }}
                                    style="width:18px;height:18px;accent-color:var(--admin-primary);cursor:pointer;">
                                Test Modu
                            </label>
                        </div>
                    </div>

                    <div class="p-3" style="border-radius:10px;background:#f0fdf4;border:1px solid #bbf7d0;">
                        <div style="font-size:13px;color:#16a34a;font-weight:600;"><i class="fas fa-info-circle" style="margin-right:4px;"></i> Nasıl çalışır?</div>
                        <p style="font-size:12px;color:#334155;margin:6px 0 0;line-height:1.6;">
                            Ödeme etkinleştirilip sağlayıcı yapılandırıldığında, müşteriler transfer veya aktivite rezervasyonu sonrası güvenli ödeme formu görecek.
                            Ödeme kapalıysa, rezervasyonlar bildirim olarak alınır ve ödemeyi manuel olarak ayarlayabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notification Sound --}}
        <div class="col-12 mb-4">
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h5><i class="fas fa-volume-up" style="color:var(--admin-primary);margin-right:8px;"></i> Bildirim Sesi</h5>
                </div>
                <div style="padding:24px;">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3">
                            <label class="settings-label">Ses Dosyası Yükle</label>
                            @if(!empty($ayar->notification_sound))
                                <div class="mb-2 d-flex align-items-center" style="gap:12px;">
                                    <span style="font-size:13px;color:var(--admin-text-light);"><i class="fas fa-music" style="margin-right:4px;"></i> {{ $ayar->notification_sound }}</span>
                                    <button type="button" onclick="document.getElementById('notifPreview').play();" class="btn-admin" style="background:#d1fae5;color:#059669;padding:6px 14px;font-size:12px;">
                                        <i class="fas fa-play"></i> Dinle
                                    </button>
                                    <label style="font-size:13px;color:#dc2626;cursor:pointer;font-weight:500;">
                                        <input type="checkbox" name="remove_notification_sound" value="1" style="margin-right:4px;"> Kaldır
                                    </label>
                                    <audio id="notifPreview" src="{{ asset('tema/uploads/sounds/' . $ayar->notification_sound) }}" preload="auto"></audio>
                                </div>
                            @endif
                            <input type="file" name="notification_sound" class="form-control-file" accept="audio/*">
                            <small style="color:var(--admin-text-light);font-size:12px;">WAV, MP3, OGG — maks 2MB. Yeni bildirim geldiğinde bu ses çalar.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin btn-admin-primary" style="padding:14px 40px;font-size:15px;border-radius:10px;">
        <i class="fas fa-save"></i> Ayarları Kaydet
    </button>
</form>

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
        padding: 12px 14px;
        resize: vertical;
    }
    .gap-2 { gap: 10px; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    function syncColor(pickerId) {
        var picker = document.getElementById(pickerId);
        var text = document.getElementById(pickerId + '_text');
        var prev = document.getElementById(
            pickerId === 'renk1' ? 'prevPrimary' :
            pickerId === 'renk2' ? 'prevSecondary' : 'prevDark'
        );
        function update() {
            text.value = picker.value;
            prev.style.background = picker.value;
        }
        picker.addEventListener('input', update);
        update();
    }
    syncColor('renk1');
    syncColor('renk2');
    syncColor('renk3');

})();
</script>
@endpush
@endsection
