@extends('layouts.admin')

@section('title', 'Aktiviteler')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-star" style="color:var(--admin-primary);margin-right:8px;"></i> Aktiviteler</h5>
        <a href="{{ route('admin.activities.create') }}" class="btn-admin btn-admin-primary" style="font-size:13px;padding:8px 16px;">
            <i class="fas fa-plus"></i> Aktivite Ekle
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Fiyat</th>
                    <th>Etiket</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->sira }}</td>
                    <td>
                        @if($activity->image)
                            <img src="{{ asset('tema/uploads/activities/' . $activity->image) }}" style="max-width:80px;max-height:50px;border-radius:6px;object-fit:cover;">
                        @else
                            <div style="width:80px;height:50px;border-radius:6px;background:linear-gradient(135deg,#0b1d33,#0066cc);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-camera" style="color:rgba(255,255,255,0.3);"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $activity->title }}</strong>
                        <div style="font-size:12px;color:var(--admin-text-light);">{{ Str::limit($activity->description, 50) }}</div>
                    </td>
                    <td>
                        @if($activity->price > 0)
                            <span style="font-weight:700;color:var(--admin-primary);">£{{ number_format($activity->price, 2) }}</span>
                        @else
                            <span style="color:var(--admin-text-light);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($activity->badge)
                            <span class="type-badge activity">{{ $activity->badge }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($activity->durum == 1)
                            <span class="type-badge transfer">Aktif</span>
                        @else
                            <span class="type-badge" style="background:#fee2e2;color:#dc2626;">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn-admin" style="background:#fef3c7;color:#b45309;padding:6px 12px;font-size:12px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
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
                    <td colspan="7" class="text-center" style="padding:40px;color:var(--admin-text-light);">
                        <i class="fas fa-star" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        Henüz aktivite yok. İlk aktivitenizi ekleyin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $activities->links() }}</div>
@endsection
