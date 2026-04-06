@extends('layouts.admin')

@section('title', 'Mesaj Detayı')

@section('content')
<div class="admin-table-card" style="max-width:700px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-envelope-open" style="color:var(--admin-primary);margin-right:8px;"></i> {{ $message->name }} adlı kişiden mesaj</h5>
        <a href="{{ route('admin.contacts.index') }}" class="btn-admin" style="background:var(--admin-bg);color:var(--admin-text);padding:8px 16px;font-size:13px;">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
    <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:100px 1fr;gap:12px;font-size:14px;margin-bottom:24px;">
            <span style="color:var(--admin-text-light);font-weight:600;">Ad Soyad</span>
            <span style="color:var(--admin-dark);font-weight:600;">{{ $message->name }}</span>
            <span style="color:var(--admin-text-light);font-weight:600;">E-posta</span>
            <span><a href="mailto:{{ $message->email }}" style="color:var(--admin-primary);">{{ $message->email }}</a></span>
            <span style="color:var(--admin-text-light);font-weight:600;">Konu</span>
            <span style="color:var(--admin-dark);font-weight:600;">{{ $message->subject }}</span>
            <span style="color:var(--admin-text-light);font-weight:600;">Tarih</span>
            <span>{{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('d M Y H:i') : '—' }}</span>
        </div>
        <div style="background:#f8fafc;border-radius:10px;padding:20px;font-size:15px;line-height:1.8;color:var(--admin-text);">
            {{ $message->message }}
        </div>
    </div>
</div>
@endsection
