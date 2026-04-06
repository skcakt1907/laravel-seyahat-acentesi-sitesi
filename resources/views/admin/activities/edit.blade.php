@extends('layouts.admin')

@section('title', 'Aktivite Düzenle')

@section('content')
<div class="admin-table-card" style="max-width:700px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-edit" style="color:var(--admin-primary);margin-right:8px;"></i> Aktivite Düzenle</h5>
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

        <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="settings-label">Başlık *</label>
                <input type="text" name="title" class="settings-input" value="{{ old('title', $activity->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="settings-label">Açıklama *</label>
                <textarea name="description" class="settings-input" rows="4" style="height:auto;" required>{{ old('description', $activity->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="settings-label">Kapak Görseli</label>
                @if($activity->image)
                    <div class="mb-2 img-remove-wrap" id="coverWrap">
                        <img src="{{ asset('tema/uploads/activities/' . $activity->image) }}" style="max-width:200px;border-radius:8px;" id="coverImg">
                        <input type="hidden" name="remove_image" id="removeCoverInput" value="0">
                        <button type="button" class="img-remove-btn" onclick="toggleCoverRemove()" title="Kaldır">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                <input type="file" name="image" class="form-control-file" accept="image/*">
                <small style="color:var(--admin-text-light);font-size:12px;">Mevcut görseli korumak için boş bırakın</small>
            </div>

            <div class="mb-3">
                <label class="settings-label">Galeri Görselleri <small style="color:var(--admin-text-light);font-weight:400;">(daha fazla ekle)</small></label>
                @php $gallery = $activity->gallery ? json_decode($activity->gallery, true) : []; @endphp
                @if(!empty($gallery))
                    <div class="d-flex flex-wrap mb-2" style="gap:8px;">
                        @foreach($gallery as $idx => $gImg)
                            <div style="position:relative;" class="gallery-item" id="galleryItem{{ $idx }}">
                                <img src="{{ asset('tema/uploads/activities/' . $gImg) }}" style="width:80px;height:60px;object-fit:cover;border-radius:6px;" class="gallery-thumb">
                                <input type="hidden" name="remove_gallery[]" value="" class="gallery-remove-input" id="galleryInput{{ $idx }}">
                                <button type="button" class="img-remove-btn" onclick="toggleGalleryRemove({{ $idx }})" title="Kaldır">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="gallery[]" class="form-control-file" accept="image/*" multiple>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Fiyat (£)</label>
                    <input type="number" name="price" class="settings-input" value="{{ old('price', $activity->price) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Etiket Metni</label>
                    <input type="text" name="badge" class="settings-input" value="{{ old('badge', $activity->badge) }}" placeholder="Popular, New, Hot">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="settings-label">Sıra</label>
                    <input type="number" name="sira" class="settings-input" value="{{ old('sira', $activity->sira) }}">
                </div>
            </div>

            <div class="mb-3">
                <label style="font-size:14px;font-weight:500;cursor:pointer;">
                    <input type="checkbox" name="durum" value="1" {{ old('durum', $activity->durum) ? 'checked' : '' }} style="margin-right:6px;"> Aktif
                </label>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary w-100" style="padding:14px;font-size:15px;border-radius:8px;">
                <i class="fas fa-save"></i> Aktiviteyi Güncelle
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

    .img-remove-wrap { position: relative; display: inline-block; }
    .img-remove-btn {
        position: absolute; top: -8px; right: -8px;
        width: 24px; height: 24px; border-radius: 50%;
        background: #dc2626; color: #fff; border: 2px solid #fff;
        font-size: 11px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .img-remove-btn:hover { background: #b91c1c; transform: scale(1.15); }
    .img-remove-wrap.marked img, .gallery-item.marked .gallery-thumb {
        opacity: 0.3; filter: grayscale(1);
    }
    .img-remove-wrap.marked .img-remove-btn, .gallery-item.marked .img-remove-btn {
        background: #10b981;
    }
</style>
@endpush

@push('scripts')
<script>
function toggleCoverRemove() {
    var wrap = document.getElementById('coverWrap');
    var input = document.getElementById('removeCoverInput');
    var marked = wrap.classList.toggle('marked');
    input.value = marked ? '1' : '0';
}

function toggleGalleryRemove(idx) {
    var item = document.getElementById('galleryItem' + idx);
    var input = document.getElementById('galleryInput' + idx);
    var marked = item.classList.toggle('marked');
    input.value = marked ? idx : '';
}
</script>
@endpush
@endsection
