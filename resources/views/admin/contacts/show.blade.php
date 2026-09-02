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

        @if(!empty($message->admin_reply))
            <div style="margin-top:24px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;padding:18px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong style="color:#065f46;font-size:13px;"><i class="fas fa-reply"></i> Yanıtınız</strong>
                    <span style="font-size:12px;color:#64748b;">{{ $message->replied_at ? \Carbon\Carbon::parse($message->replied_at)->format('d M Y H:i') : '' }}</span>
                </div>
                <div style="white-space:pre-wrap;color:#064e3b;font-size:14px;line-height:1.7;">{{ $message->admin_reply }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="margin-top:20px;border-radius:10px;border:none;font-size:14px;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-top:20px;border-radius:10px;border:none;font-size:14px;"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.contacts.reply', $message->id) }}" method="POST" style="margin-top:24px;">
            @csrf
            <label style="display:block;font-size:13px;font-weight:600;color:var(--admin-dark);margin-bottom:8px;">
                <i class="fas fa-paper-plane" style="color:var(--admin-primary);"></i>
                {{ !empty($message->admin_reply) ? 'Tekrar Yanıtla' : 'Yanıtla' }}
            </label>
            <textarea name="reply" rows="6" required placeholder="Mesajınızı yazın..."
                style="width:100%;border:2px solid var(--admin-border);border-radius:10px;padding:14px;font-family:inherit;font-size:14px;line-height:1.6;resize:vertical;outline:none;"
                onfocus="this.style.borderColor='var(--admin-primary)'" onblur="this.style.borderColor='var(--admin-border)'"></textarea>
            <div style="margin-top:12px;text-align:right;">
                <button type="submit" class="btn-admin" style="padding:10px 22px;font-size:14px;">
                    <i class="fas fa-paper-plane"></i> Gönder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
