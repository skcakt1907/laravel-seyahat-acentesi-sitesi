@extends('layouts.admin')

@section('title', 'Notlarım')

@section('content')
<div class="admin-page-hero">
    <div class="admin-page-hero-inner">
        <div>
            <h1><span class="ph-icon"><i class="fas fa-sticky-note"></i></span> Notlarım</h1>
            <div class="ph-sub">{{ ($notes ?? collect())->count() }} not · kişisel hatırlatmalar ve görseller</div>
        </div>
    </div>
</div>

{{-- Add Note Form --}}
<div class="admin-table-card mb-4" style="max-width:600px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-plus" style="color:var(--admin-primary);margin-right:8px;"></i> Not Ekle</h5>
    </div>
    <div style="padding:24px;">
        <form action="{{ route('admin.notes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="text" name="title" class="note-input" placeholder="Not başlığı..." required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="note-input" rows="3" style="height:auto;" placeholder="Notunuzu yazın..."></textarea>
            </div>
            <div class="mb-3">
                <label class="note-img-upload" id="addNoteImgLabel">
                    <i class="fas fa-image"></i> <span>Görsel Ekle</span>
                    <input type="file" name="image" accept="image/*" style="display:none;" onchange="this.parentElement.querySelector('span').textContent=this.files[0]?.name||'Görsel Ekle';">
                </label>
            </div>
            <div class="d-flex align-items-center" style="gap:12px;flex-wrap:wrap;">
                <div class="d-flex align-items-center" style="gap:6px;">
                    <span style="font-size:12px;color:var(--admin-text-light);font-weight:600;">Renk:</span>
                    @foreach(['#fef3c7','#dbeafe','#d1fae5','#fce7f3','#ede9fe','#fed7aa','#e2e8f0'] as $c)
                        <label style="width:24px;height:24px;border-radius:50%;background:{{ $c }};cursor:pointer;border:2px solid {{ $c == '#fef3c7' ? 'var(--admin-primary)' : 'transparent' }};display:inline-block;">
                            <input type="radio" name="color" value="{{ $c }}" {{ $c == '#fef3c7' ? 'checked' : '' }} style="display:none;" onchange="this.parentElement.parentElement.querySelectorAll('label').forEach(l=>l.style.borderColor='transparent');this.parentElement.style.borderColor='var(--admin-primary)';">
                        </label>
                    @endforeach
                </div>
                <label style="font-size:13px;cursor:pointer;color:var(--admin-text-light);">
                    <input type="checkbox" name="pinned" value="1" style="margin-right:4px;"> Sabitle
                </label>
                <button type="submit" class="btn-admin btn-admin-primary" style="margin-left:auto;padding:10px 24px;font-size:13px;">
                    <i class="fas fa-save"></i> Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Notes Grid --}}
@if($notes->count() > 0)
<div class="notes-grid">
    @foreach($notes as $note)
    <div class="note-card" style="background:{{ $note->color }};">
        @if($note->pinned)
            <div class="note-pin"><i class="fas fa-thumbtack"></i></div>
        @endif

        @if($note->image)
        <div class="note-image" onclick="openLightbox('{{ asset('tema/uploads/notes/' . $note->image) }}')">
            <img src="{{ asset('tema/uploads/notes/' . $note->image) }}" alt="">
            <div class="note-image-zoom"><i class="fas fa-search-plus"></i></div>
        </div>
        @endif

        <form action="{{ route('admin.notes.update', $note->id) }}" method="POST" enctype="multipart/form-data" class="note-edit-form" id="noteForm{{ $note->id }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="color" value="{{ $note->color }}">
            <input type="hidden" name="pinned" value="{{ $note->pinned }}">
            <input type="text" name="title" value="{{ $note->title }}" class="note-title-input">
            <textarea name="content" class="note-content-input" rows="3">{{ $note->content }}</textarea>
            <div class="note-img-actions">
                @if($note->image)
                    <label class="note-img-action-btn remove">
                        <input type="checkbox" name="remove_image" value="1" style="display:none;" onchange="this.parentElement.classList.toggle('active');">
                        <i class="fas fa-trash"></i> Görseli kaldır
                    </label>
                @endif
                <label class="note-img-action-btn upload">
                    <i class="fas fa-image"></i> <span>{{ $note->image ? 'Değiştir' : 'Görsel ekle' }}</span>
                    <input type="file" name="image" accept="image/*" style="display:none;" onchange="this.parentElement.querySelector('span').textContent=this.files[0]?.name||'Görsel ekle';">
                </label>
            </div>
        </form>

        <div class="note-footer">
            <span class="note-date">{{ \Carbon\Carbon::parse($note->updated_at)->diffForHumans() }}</span>
            <div class="note-actions">
                <button type="submit" form="noteForm{{ $note->id }}" class="note-btn save" title="Kaydet"><i class="fas fa-check"></i></button>
                <form action="{{ route('admin.notes.togglePin', $note->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="note-btn pin {{ $note->pinned ? 'active' : '' }}" title="{{ $note->pinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' }}"><i class="fas fa-thumbtack"></i></button>
                </form>
                <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu notu silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="note-btn delete" title="Sil"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center" style="padding:60px;color:var(--admin-text-light);">
    <i class="fas fa-sticky-note" style="font-size:48px;display:block;margin-bottom:16px;opacity:0.2;"></i>
    <p style="font-size:15px;margin:0;">Henüz not yok. Yukarıdan ilk notunuzu oluşturun.</p>
</div>
@endif

{{-- Lightbox --}}
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img id="lightboxImg" src="" alt="">
</div>

@push('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endpush

@push('styles')
<style>
    .note-input {
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
    .note-input:focus { border-color: var(--admin-primary); outline: none; box-shadow: 0 0 0 3px rgba(0,102,204,0.1); }
    textarea.note-input { padding: 12px 14px; resize: vertical; }

    .notes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .note-card {
        border-radius: 12px;
        padding: 20px;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.2s;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .note-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .note-pin {
        position: absolute;
        top: 10px;
        right: 12px;
        color: #b45309;
        font-size: 13px;
    }

    .note-title-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 16px;
        font-weight: 700;
        color: var(--admin-dark);
        font-family: 'Poppins', sans-serif;
        margin-bottom: 8px;
        padding: 0;
        outline: none;
    }
    .note-title-input:focus {
        border-bottom: 1px solid rgba(0,0,0,0.15);
    }

    .note-content-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 13px;
        color: #334155;
        font-family: 'Poppins', sans-serif;
        resize: none;
        padding: 0;
        outline: none;
        line-height: 1.6;
    }
    .note-content-input:focus {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .note-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid rgba(0,0,0,0.06);
    }

    .note-date {
        font-size: 11px;
        color: rgba(0,0,0,0.35);
        font-weight: 500;
    }

    .note-actions {
        display: flex;
        gap: 4px;
    }

    .note-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: none;
        background: rgba(0,0,0,0.06);
        color: rgba(0,0,0,0.4);
        font-size: 11px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .note-btn:hover { background: rgba(0,0,0,0.12); color: rgba(0,0,0,0.7); }
    .note-btn.save:hover { background: #d1fae5; color: #059669; }
    .note-btn.pin.active { color: #b45309; background: rgba(180,83,9,0.12); }
    .note-btn.delete:hover { background: #fee2e2; color: #dc2626; }

    .note-image {
        margin: -20px -20px 14px -20px;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        max-height: 200px;
    }
    .note-image img {
        width: 100%;
        height: 100%;
        max-height: 200px;
        object-fit: cover;
        display: block;
    }

    .note-img-upload {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 2px dashed var(--admin-border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--admin-text-light);
        cursor: pointer;
        transition: all 0.2s;
    }
    .note-img-upload:hover {
        border-color: var(--admin-primary);
        color: var(--admin-primary);
    }

    .note-img-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }
    .note-img-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
        color: var(--admin-text-light);
        background: rgba(0,0,0,0.04);
    }
    .note-img-action-btn:hover { background: rgba(0,0,0,0.08); }
    .note-img-action-btn.upload:hover { color: var(--admin-primary); }
    .note-img-action-btn.remove:hover, .note-img-action-btn.remove.active { color: #dc2626; background: #fee2e2; }

    .note-image { position: relative; cursor: pointer; }
    .note-image-zoom {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(0,0,0,0.5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .note-image:hover .note-image-zoom { opacity: 1; }

    .lightbox-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        cursor: zoom-out;
    }
    .lightbox-overlay.open { display: flex; }
    .lightbox-overlay img {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .lightbox-close {
        position: fixed;
        top: 20px;
        right: 24px;
        color: #fff;
        font-size: 28px;
        cursor: pointer;
        z-index: 10000;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .lightbox-close:hover { opacity: 1; }
</style>
@endpush
@endsection
