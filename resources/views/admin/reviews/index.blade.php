@extends('layouts.admin')

@section('title', 'Yorumlar')

@section('content')
<div class="admin-page-hero">
    <div class="admin-page-hero-inner">
        <div>
            <h1><span class="ph-icon"><i class="fas fa-comment-dots"></i></span> Müşteri Yorumları</h1>
            <div class="ph-sub">{{ $reviews->count() ?? 0 }} yorum · gelen müşteri yorumlarını yönet</div>
        </div>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-list" style="color:var(--admin-primary);margin-right:8px;"></i> Tüm Yorumlar</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Misafir</th>
                    <th>Konum</th>
                    <th>Puan</th>
                    <th>Yorum</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td><strong>{{ $review->name }}</strong></td>
                    <td>{{ $review->location ?? '—' }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="font-size:12px;color:{{ $i <= $review->rating ? '#f59e0b' : '#e2e8f0' }};"></i>
                        @endfor
                    </td>
                    <td style="max-width:300px;">{{ Str::limit($review->comment, 80) }}</td>
                    <td>
                        @if($review->approved)
                            <span class="type-badge transfer">Onaylı</span>
                        @else
                            <span class="type-badge" style="background:#fef3c7;color:#b45309;">Beklemede</span>
                        @endif
                    </td>
                    <td>{{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d M Y') : '—' }}</td>
                    <td style="white-space:nowrap;">
                        <button type="button" class="btn-admin" style="background:#dbeafe;color:#1d4ed8;padding:6px 12px;font-size:12px;" title="Görüntüle"
                            onclick="showReview('{{ addslashes($review->name) }}', '{{ $review->location ?? 'Misafir' }}', {{ $review->rating }}, '{{ addslashes($review->comment) }}', '{{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d M Y H:i') : '-' }}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(!$review->approved)
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-admin" style="background:#d1fae5;color:#059669;padding:6px 12px;font-size:12px;" title="Onayla">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-admin" style="background:#fef3c7;color:#b45309;padding:6px 12px;font-size:12px;" title="Reddet">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin" style="background:#fee2e2;color:#dc2626;padding:6px 12px;font-size:12px;" title="Sil">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:40px;color:var(--admin-text-light);">
                        <i class="fas fa-comments" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        Henüz yorum yok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $reviews->links() }}</div>

{{-- Review Detail Modal --}}
<div class="rv-overlay" id="rvOverlay" onclick="closeReview()"></div>
<div class="rv-modal" id="rvModal">
    <button class="rv-close" onclick="closeReview()">&times;</button>
    <div class="rv-header">
        <div class="rv-avatar" id="rvAvatar"></div>
        <div>
            <strong id="rvName" style="font-size:16px;color:var(--admin-dark);"></strong>
            <div id="rvLocation" style="font-size:13px;color:var(--admin-text-light);"></div>
        </div>
    </div>
    <div class="rv-stars" id="rvStars"></div>
    <div class="rv-comment" id="rvComment"></div>
    <div class="rv-date" id="rvDate"></div>
</div>

@push('styles')
<style>
.rv-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
    backdrop-filter:blur(3px); z-index:9998;
}
.rv-overlay.open { display:block; }
.rv-modal {
    display:none; position:fixed; top:50%; left:50%;
    transform:translate(-50%,-50%) scale(0.9);
    background:#fff; border-radius:16px; width:480px; max-width:92vw;
    padding:32px; z-index:9999;
    box-shadow:0 30px 80px rgba(0,0,0,0.25);
    opacity:0; transition:all 0.25s ease;
}
.rv-modal.open { display:block; opacity:1; transform:translate(-50%,-50%) scale(1); }
.rv-close {
    position:absolute; top:14px; right:16px; background:none; border:none;
    font-size:22px; color:var(--admin-text-light); cursor:pointer;
}
.rv-close:hover { color:var(--admin-dark); }
.rv-header { display:flex; align-items:center; gap:14px; margin-bottom:18px; }
.rv-avatar {
    width:48px; height:48px; border-radius:50%; background:var(--admin-primary);
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:20px; font-weight:700; flex-shrink:0;
}
.rv-stars { margin-bottom:16px; }
.rv-stars i { font-size:18px; color:#f59e0b; margin-right:2px; }
.rv-stars i.empty { color:#e2e8f0; }
.rv-comment {
    font-size:15px; line-height:1.8; color:#334155;
    background:#f8fafc; border-radius:12px; padding:20px;
    border-left:4px solid #f59e0b; margin-bottom:16px;
    font-style:italic;
}
.rv-date { font-size:12px; color:#94a3b8; text-align:right; }
</style>
@endpush

@push('scripts')
<script>
function showReview(name, location, rating, comment, date) {
    document.getElementById('rvAvatar').textContent = name.charAt(0).toUpperCase();
    document.getElementById('rvName').textContent = name;
    document.getElementById('rvLocation').textContent = location;
    document.getElementById('rvComment').textContent = '"' + comment + '"';
    document.getElementById('rvDate').innerHTML = '<i class="fas fa-clock" style="margin-right:4px;"></i> ' + date;

    var starsHtml = '';
    for (var i = 1; i <= 5; i++) {
        starsHtml += '<i class="fas fa-star' + (i > rating ? ' empty' : '') + '"></i>';
    }
    document.getElementById('rvStars').innerHTML = starsHtml;

    document.getElementById('rvOverlay').classList.add('open');
    document.getElementById('rvModal').classList.add('open');
}
function closeReview() {
    document.getElementById('rvOverlay').classList.remove('open');
    document.getElementById('rvModal').classList.remove('open');
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeReview(); });
</script>
@endpush
@endsection
