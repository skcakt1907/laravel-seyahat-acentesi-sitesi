@extends('layouts.admin')

@section('title', 'Kontrol Paneli')

@section('content')
{{-- Stats Row --}}
<div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#dbeafe;color:#1d4ed8;">
                <i class="fas fa-users"></i>
            </div>
            <h3>{{ $stats['total_customers'] }}</h3>
            <p>Toplam Müşteri</p>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#d1fae5;color:#059669;">
                <i class="fas fa-shuttle-van"></i>
            </div>
            <h3>{{ $stats['total_transfers'] }}</h3>
            <p>Transfer Rezervasyonları</p>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fef3c7;color:#b45309;">
                <i class="fas fa-star"></i>
            </div>
            <h3>{{ $stats['total_activities'] }}</h3>
            <p>Aktivite Rezervasyonları</p>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#ede9fe;color:#7c3aed;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h3>{{ $stats['today_customers'] }}</h3>
            <p>Bugünkü Rezervasyonlar</p>
        </div>
    </div>
</div>

{{-- Quick Stats --}}
<div class="row mb-4">
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="stat-card" style="border-left:4px solid var(--admin-primary);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p style="font-size:13px;color:var(--admin-text-light);margin:0;">Bu Hafta</p>
                    <h3 style="font-size:24px;margin:4px 0 0;">{{ $stats['week_customers'] }}</h3>
                </div>
                <i class="fas fa-chart-line" style="font-size:28px;color:var(--admin-primary);opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="stat-card" style="border-left:4px solid var(--admin-secondary);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p style="font-size:13px;color:var(--admin-text-light);margin:0;">Bu Ay</p>
                    <h3 style="font-size:24px;margin:4px 0 0;">{{ $stats['month_customers'] }}</h3>
                </div>
                <i class="fas fa-calendar-alt" style="font-size:28px;color:var(--admin-secondary);opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 mb-3">
        <div class="stat-card" style="border-left:4px solid #10b981;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p style="font-size:13px;color:var(--admin-text-light);margin:0;">Aktif Sliderlar</p>
                    <h3 style="font-size:24px;margin:4px 0 0;">{{ $stats['active_sliders'] }} / {{ $stats['total_sliders'] }}</h3>
                </div>
                <i class="fas fa-images" style="font-size:28px;color:#10b981;opacity:0.3;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Recent Customers Table --}}
<div class="admin-table-card">
    <div class="admin-table-header">
        <h5><i class="fas fa-clock" style="color:var(--admin-primary);margin-right:8px;"></i> Son Müşteriler</h5>
        <div>
            <a href="{{ route('admin.customers.export') }}" class="btn-admin btn-admin-success" style="font-size:13px;padding:8px 16px;">
                <i class="fas fa-download"></i> CSV İndir
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn-admin btn-admin-primary" style="font-size:13px;padding:8px 16px;">
                Tümünü Gör <i class="fas fa-arrow-right"></i>
            </a>
        </div>
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
                @forelse($recent_customers as $customer)
                <tr>
                    <td><strong>#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <span class="type-badge {{ $customer->type }}">
                            {{ $customer->type === 'transfer' ? 'Transfer' : 'Activity' }}
                        </span>
                    </td>
                    <td>{{ $customer->type === 'activity' ? $customer->activity_name : $customer->package }}</td>
                    <td>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d M Y H:i') : '-' }}</td>
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
@endsection
