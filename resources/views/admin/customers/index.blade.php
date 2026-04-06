@extends('layouts.admin')

@section('title', 'Müşteriler')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-users" style="color:var(--admin-primary);margin-right:8px;"></i> Müşteriler <span style="font-weight:400;color:var(--admin-text-light);font-size:14px;margin-left:8px;">{{ $customers->total() }} toplam</span></h5>
        <a href="{{ route('admin.customers.export') }}" class="btn-admin btn-admin-success" style="font-size:13px;padding:8px 16px;">
            <i class="fas fa-download"></i> CSV İndir
        </a>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:40px;color:var(--admin-text-light);">
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
