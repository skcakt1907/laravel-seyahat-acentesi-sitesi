@extends('layouts.admin')

@section('title', 'Aktivite Ekle')

@section('content')
<div class="admin-table-card" style="max-width:700px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-plus-circle" style="color:var(--admin-primary);margin-right:8px;"></i> Aktivite Ekle</h5>
        <a href="{{ route('admin.activities.index') }}" class="btn-admin" style="background:var(--admin-bg);color:var(--admin-text);padding:8px 16px;font-size:13px;">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
    <div style="padding:28px;">
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:8px;border:none;font-size:14px;">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="settings-label">Başlık *</label>
                <input type="text" name="title" class="settings-input" value="{{ old('title') }}" required placeholder="e.g. Safari, Jeep Tour">
            </div>

            <div class="mb-3">
                <label class="settings-label">Açıklama *</label>
                <textarea name="description" class="settings-input" rows="4" style="height:auto;" required>{{ old('description') }}</textarea>
            </div>

            @include('admin.partials.ceviri-kutulari', [
                'kayit'   => (object) [],
                'alanlar' => [
                    'title'       => ['etiket' => 'Başlık',   'tip' => 'input'],
                    'description' => ['etiket' => 'Açıklama', 'tip' => 'textarea'],
                ],
            ])

            <div class="mb-3">
                <label class="settings-label">Kapak Görseli</label>
                <input type="file" name="image" class="form-control-file" accept="image/*">
                <small style="color:var(--admin-text-light);font-size:12px;">Recommended: 600x400px</small>
            </div>

            <div class="mb-3">
                <label class="settings-label">Galeri Görselleri <small style="color:var(--admin-text-light);font-weight:400;">(çoklu)</small></label>
                <input type="file" name="gallery[]" class="form-control-file" accept="image/*" multiple>
                <small style="color:var(--admin-text-light);font-size:12px;">Detay galerisi için birden fazla görsel seçin</small>
            </div>

            <div class="mb-3">
                <label class="settings-label">Kategori</label>
                <select name="category" class="settings-input" style="height:46px;">
                    @foreach(['Boat Trips','Water Sports','Safari & Adventure','Day Trips','Cultural','Other'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Fiyat (£)</label>
                    <input type="number" name="price" class="settings-input" value="{{ old('price', '0') }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Etiket Metni</label>
                    <input type="text" name="badge" class="settings-input" value="{{ old('badge', 'Popular') }}" placeholder="Popular, New, Hot">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Sıra</label>
                    <input type="number" name="sira" class="settings-input" value="{{ old('sira', '0') }}">
                </div>
            </div>

            <div class="mb-3">
                <label style="font-size:14px;font-weight:500;cursor:pointer;">
                    <input type="checkbox" name="durum" value="1" checked style="margin-right:6px;"> Aktif
                </label>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary w-100" style="padding:14px;font-size:15px;border-radius:8px;">
                <i class="fas fa-save"></i> Aktiviteyi Kaydet
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
    .settings-label { display:block;font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px; }
    .settings-input { width:100%;height:46px;border:2px solid var(--admin-border);border-radius:8px;padding:0 14px;font-size:14px;font-family:'Poppins',sans-serif;color:var(--admin-dark);transition:all 0.2s;background:#fff; }
    .settings-input:focus { border-color:var(--admin-primary);outline:none;box-shadow:0 0 0 3px rgba(0,102,204,0.1); }
    textarea.settings-input { padding:12px 14px;resize:vertical; }
</style>
@endpush
@endsection
