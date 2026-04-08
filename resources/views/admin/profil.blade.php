@extends('layouts.admin')

@section('title', 'Profilim')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2 text-primary"></i> Profilim</h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Kullanıcı Adı</label>
                                <div class="fw-bold">{{ $admin->kullaniciadi }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Ad</label>
                                <div class="fw-bold">{{ $admin->adi ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">E-posta</label>
                                <div>{{ $admin->email ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Son Giriş</label>
                                <div>{{ $admin->son_giris ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="fas fa-key me-2"></i> Şifre Değiştir</h6>

                    <form action="{{ route('admin.profil.sifre') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Mevcut Şifre</label>
                            <input type="password" name="mevcut_sifre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Yeni Şifre</label>
                            <input type="password" name="yeni_sifre" class="form-control" minlength="8" required>
                            <small class="text-muted">En az 8 karakter olmalı.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Yeni Şifre (Tekrar)</label>
                            <input type="password" name="yeni_sifre_confirmation" class="form-control" minlength="8" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Şifreyi Güncelle
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
