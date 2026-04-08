@extends('layouts.admin')

@section('title', 'Slider Yönetimi')

@section('content')
<div class="admin-page-hero">
    <div class="admin-page-hero-inner">
        <div>
            <h1><span class="ph-icon"><i class="fas fa-images"></i></span> Slider Yönetimi</h1>
            <div class="ph-sub">{{ $sliderlar->count() ?? 0 }} slayt · ana sayfa hero görselleri / videoları</div>
        </div>
        <div class="admin-page-hero-actions">
            <a href="{{ route('admin.slider.ekle') }}" class="btn-admin alt"><i class="fas fa-plus"></i> Slider Ekle</a>
        </div>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-list" style="color:var(--admin-primary);margin-right:8px;"></i> Tüm Slaytlar</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Medya</th>
                    <th>Başlık</th>
                    <th>Link</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliderlar as $slider)
                <tr>
                    <td>{{ $slider->sira }}</td>
                    <td>
                        @if(($slider->media_type ?? 'image') === 'video' && !empty($slider->video))
                            <video src="{{ asset('tema/uploads/slider/videos/' . $slider->video) }}" style="max-width:100px;max-height:56px;border-radius:6px;" muted></video>
                        @elseif($slider->resim)
                            <img src="{{ asset('tema/uploads/slider/' . $slider->resim) }}" alt="{{ $slider->adi }}" style="max-width:100px;max-height:56px;border-radius:6px;object-fit:cover;">
                        @else
                            <span style="color:var(--admin-text-light);">—</span>
                        @endif
                    </td>
                    <td><strong>{{ $slider->adi }}</strong></td>
                    <td>{{ $slider->link ?? '—' }}</td>
                    <td>
                        @if($slider->durum == 1)
                            <span class="type-badge transfer">Aktif</span>
                        @else
                            <span class="type-badge" style="background:#fee2e2;color:#dc2626;">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.slider.duzenle', $slider->id) }}" class="btn-admin" style="background:#fef3c7;color:#b45309;padding:6px 12px;font-size:12px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.slider.sil', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu slider\'ı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin" style="background:#fee2e2;color:#dc2626;padding:6px 12px;font-size:12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:40px;color:var(--admin-text-light);">
                        <i class="fas fa-images" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        Henüz slider yok. İlk slider'ınızı ekleyin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $sliderlar->links() }}</div>
@endsection
