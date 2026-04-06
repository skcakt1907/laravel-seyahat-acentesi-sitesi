@extends('layouts.admin')

@section('title', 'Transfer Güzergahı Düzenle')

@section('content')
<div class="admin-table-card" style="max-width:700px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-edit" style="color:var(--admin-primary);margin-right:8px;"></i> Transfer Güzergahı Düzenle</h5>
        <a href="{{ route('admin.transfers.index') }}" class="btn-admin" style="background:var(--admin-bg);color:var(--admin-text);padding:8px 16px;font-size:13px;">
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

        <form action="{{ route('admin.transfers.update', $transfer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="settings-label">Kalkış Noktası *</label>
                    <input type="text" name="from_location" class="settings-input" value="{{ old('from_location', $transfer->from_location) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="settings-label">Varış Noktası *</label>
                    <input type="text" name="to_location" class="settings-input" value="{{ old('to_location', $transfer->to_location) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="settings-label">Görünen Başlık</label>
                <input type="text" name="title" class="settings-input" value="{{ old('title', $transfer->title) }}">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Fiyat (£)</label>
                    <input type="number" name="price" class="settings-input" value="{{ old('price', $transfer->price) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Sıra</label>
                    <input type="number" name="sira" class="settings-input" value="{{ old('sira', $transfer->sira) }}">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <label style="font-size:14px;font-weight:500;cursor:pointer;">
                        <input type="checkbox" name="durum" value="1" {{ old('durum', $transfer->durum) ? 'checked' : '' }} style="margin-right:6px;"> Aktif
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="settings-label">Açıklama</label>
                <textarea name="description" class="settings-input" rows="3" style="height:auto;">{{ old('description', $transfer->description) }}</textarea>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary w-100" style="padding:14px;font-size:15px;border-radius:8px;">
                <i class="fas fa-save"></i> Güzergahı Güncelle
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
