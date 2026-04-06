@extends('layouts.admin')

@section('title', 'Slider Ekle')

@section('content')
<div class="admin-table-card" style="max-width:700px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-plus-circle" style="color:var(--admin-primary);margin-right:8px;"></i> Yeni Slider Ekle</h5>
        <a href="{{ route('admin.slider.index') }}" class="btn-admin" style="background:var(--admin-bg);color:var(--admin-text);padding:8px 16px;font-size:13px;">
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

        <form action="{{ route('admin.slider.eklePost') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Başlık *</label>
                <input type="text" class="form-control" name="adi" value="{{ old('adi') }}" required style="height:46px;border-radius:8px;border:2px solid var(--admin-border);font-family:'Poppins',sans-serif;">
            </div>

            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Link Adresi</label>
                <input type="text" class="form-control" name="link" value="{{ old('link') }}" placeholder="https://" style="height:46px;border-radius:8px;border:2px solid var(--admin-border);font-family:'Poppins',sans-serif;">
            </div>

            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Açıklama</label>
                <textarea class="form-control" name="aciklama" rows="3" style="border-radius:8px;border:2px solid var(--admin-border);font-family:'Poppins',sans-serif;">{{ old('aciklama') }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Medya Türü *</label>
                <select class="form-control" id="media_type" name="media_type" required style="height:46px;border-radius:8px;border:2px solid var(--admin-border);font-family:'Poppins',sans-serif;">
                    <option value="image" {{ old('media_type', 'image') === 'image' ? 'selected' : '' }}>Görsel</option>
                    <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>Video</option>
                </select>
            </div>

            <div class="form-group mb-3" id="imageField">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Slider Görseli *</label>
                <input type="file" class="form-control-file" id="resim" name="resim" accept="image/*">
                <small style="color:var(--admin-text-light);font-size:12px;">Recommended: 1920x800px</small>
            </div>

            <div class="form-group mb-3 d-none" id="videoField">
                <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Slider Videosu *</label>
                <input type="file" class="form-control-file" id="video" name="video" accept="video/mp4,video/webm,video/ogg">
                <small style="color:var(--admin-text-light);font-size:12px;">MP4/WEBM/OGG — max 100MB</small>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label style="font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:6px;display:block;">Sıra</label>
                    <input type="number" class="form-control" name="sira" value="{{ old('sira', 0) }}" style="height:46px;border-radius:8px;border:2px solid var(--admin-border);font-family:'Poppins',sans-serif;">
                </div>
                <div class="col-6 d-flex align-items-end">
                    <label style="font-size:14px;font-weight:500;cursor:pointer;">
                        <input type="checkbox" name="durum" value="1" checked style="margin-right:6px;"> Aktif
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary w-100" style="padding:14px;font-size:15px;border-radius:8px;">
                <i class="fas fa-save"></i> Slider'ı Kaydet
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var mediaType = document.getElementById('media_type');
    var imageField = document.getElementById('imageField');
    var videoField = document.getElementById('videoField');

    function toggle() {
        if (mediaType.value === 'video') {
            imageField.classList.add('d-none');
            videoField.classList.remove('d-none');
        } else {
            imageField.classList.remove('d-none');
            videoField.classList.add('d-none');
        }
    }
    mediaType.addEventListener('change', toggle);
    toggle();
});
</script>
@endsection
