@extends('layouts.admin')

@section('title', 'Mesajlar')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-envelope" style="color:var(--admin-primary);margin-right:8px;"></i> İletişim Mesajları</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Konu</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="{{ !$msg->read ? 'font-weight:600;' : '' }}">
                    <td>
                        @if(!$msg->read)
                            <span style="width:8px;height:8px;border-radius:50%;background:var(--admin-primary);display:inline-block;"></span>
                        @endif
                    </td>
                    <td>{{ $msg->name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ Str::limit($msg->subject, 40) }}</td>
                    <td>{{ $msg->created_at ? \Carbon\Carbon::parse($msg->created_at)->format('d M Y H:i') : '—' }}</td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.contacts.show', $msg->id) }}" class="btn-admin" style="background:#dbeafe;color:#1d4ed8;padding:6px 12px;font-size:12px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
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
                        <i class="fas fa-envelope-open" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        Henüz mesaj yok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $messages->links() }}</div>
@endsection
