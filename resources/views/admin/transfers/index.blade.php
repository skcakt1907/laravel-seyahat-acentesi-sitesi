@extends('layouts.admin')

@section('title', 'Transfer Güzergahları')

@section('content')
<div class="cust-hero">
    <div class="cust-hero-left">
        <div class="ph-icon"><i class="fas fa-shuttle-van"></i></div>
        <div>
            <h1>Transfer Güzergahları</h1>
            <div class="ph-sub">{{ $transfers->count() }} güzergah · havalimanı transfer rotalarını yönet</div>
        </div>
    </div>
    <div class="cust-hero-actions">
        <a href="{{ route('admin.transfers.create') }}" class="btn-admin alt"><i class="fas fa-plus"></i> Güzergah Ekle</a>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-list" style="color:var(--admin-primary);margin-right:8px;"></i> Tüm Güzergahlar</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Nereden</th>
                    <th>Nereye</th>
                    <th>Başlık</th>
                    <th>Fiyat</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->sira }}</td>
                    <td><strong>{{ $transfer->from_location }}</strong></td>
                    <td><strong>{{ $transfer->to_location }}</strong></td>
                    <td>{{ $transfer->title }}</td>
                    <td>
                        @if($transfer->price > 0)
                            <span style="font-weight:700;color:var(--admin-primary);">£{{ number_format($transfer->price, 2) }}</span>
                        @else
                            <span style="color:var(--admin-text-light);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($transfer->durum == 1)
                            <span class="type-badge transfer">Aktif</span>
                        @else
                            <span class="type-badge" style="background:#fee2e2;color:#dc2626;">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.transfers.edit', $transfer->id) }}" class="btn-admin" style="background:#fef3c7;color:#b45309;padding:6px 12px;font-size:12px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.transfers.destroy', $transfer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
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
                        <i class="fas fa-route" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        Henüz güzergah yok. İlk güzergahınızı ekleyin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $transfers->links() }}</div>
@endsection
