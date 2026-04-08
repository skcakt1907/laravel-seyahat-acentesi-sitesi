@extends('layouts.admin')

@section('title', 'Müşteriler')

@section('content')
<div class="admin-page-hero">
    <div class="admin-page-hero-inner">
        <div>
            <h1><span class="ph-icon"><i class="fas fa-users"></i></span> Müşteriler</h1>
            <div class="ph-sub">{{ $customers->total() }} toplam müşteri · rezervasyon ve harcama bilgileri</div>
        </div>
        <div class="admin-page-hero-actions">
            <a href="{{ route('admin.customers.create') }}" class="btn-admin alt"><i class="fas fa-user-plus"></i> Müşteri Ekle</a>
            <a href="{{ route('admin.customers.export') }}" class="btn-admin"><i class="fas fa-download"></i> CSV İndir</a>
        </div>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-list" style="color:var(--admin-primary);margin-right:8px;"></i> Tüm Müşteriler</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Tür</th>
                    <th>Detay</th>
                    <th>Tarih</th>
                    <th>Toplam Harcama</th>
                    <th>İncele</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td><strong>#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <span class="type-badge {{ $customer->type ?? 'transfer' }}">
                            {{ ucfirst($customer->type ?? 'transfer') }}
                        </span>
                    </td>
                    <td>{{ $customer->activity_name ?: ($customer->package ?: '—') }}</td>
                    <td>{{ optional($customer->created_at)->format('d M Y H:i') }}</td>
                    <td><strong style="color:#059669;">£{{ number_format((float)($customer->total_spent ?? 0), 2) }}</strong></td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-admin btn-admin-primary" style="font-size:12px;padding:6px 12px;" title="Detayları görüntüle">
                            <i class="fas fa-eye"></i> İncele
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding:40px;color:var(--admin-text-light);">
                        <i class="fas fa-inbox" style="font-size:36px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                        Henüz müşteri yok. Rezervasyonlar burada görünecek.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($customers->hasPages())
    <div class="mt-3">{{ $customers->links() }}</div>
@endif
@endsection
