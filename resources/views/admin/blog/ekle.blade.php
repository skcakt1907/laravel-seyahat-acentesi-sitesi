@extends('layouts.admin')

@section('title', 'Yeni Blog Yazısı')
@section('page-title', 'Yeni Blog Yazısı')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Blog Yazısı Ekle</h6>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.blog.eklePost') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Dil Sekmeleri --}}
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-tr" role="tab">TR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-en" role="tab">EN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-ar" role="tab">AR</a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- Türkçe --}}
                <div class="tab-pane fade show active" id="tab-tr" role="tabpanel">
                    <div class="form-group">
                        <label>Başlık (TR) *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Özet (TR)</label>
                        <textarea name="ozet" class="form-control" rows="3">{{ old('ozet') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (TR) *</label>
                        <textarea name="icerik" class="form-control" rows="8" required>{{ old('icerik') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (TR)</label>
                        <input type="text" name="seo_baslik" class="form-control" value="{{ old('seo_baslik') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (TR)</label>
                        <textarea name="seo_aciklama" class="form-control" rows="2">{{ old('seo_aciklama') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (TR)</label>
                        <input type="text" name="seo_anahtar" class="form-control" value="{{ old('seo_anahtar') }}">
                    </div>
                </div>

                {{-- İngilizce --}}
                <div class="tab-pane fade" id="tab-en" role="tabpanel">
                    <div class="form-group">
                        <label>Başlık (EN)</label>
                        <input type="text" name="baslik_en" class="form-control" value="{{ old('baslik_en') }}">
                    </div>
                    <div class="form-group">
                        <label>Özet (EN)</label>
                        <textarea name="ozet_en" class="form-control" rows="3">{{ old('ozet_en') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (EN)</label>
                        <textarea name="icerik_en" class="form-control" rows="8">{{ old('icerik_en') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (EN)</label>
                        <input type="text" name="seo_baslik_en" class="form-control" value="{{ old('seo_baslik_en') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (EN)</label>
                        <textarea name="seo_aciklama_en" class="form-control" rows="2">{{ old('seo_aciklama_en') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (EN)</label>
                        <input type="text" name="seo_anahtar_en" class="form-control" value="{{ old('seo_anahtar_en') }}">
                    </div>
                </div>

                {{-- Arapça --}}
                <div class="tab-pane fade" id="tab-ar" role="tabpanel">
                    <div class="form-group">
                        <label>Başlık (AR)</label>
                        <input type="text" name="baslik_ar" class="form-control" value="{{ old('baslik_ar') }}">
                    </div>
                    <div class="form-group">
                        <label>Özet (AR)</label>
                        <textarea name="ozet_ar" class="form-control" rows="3">{{ old('ozet_ar') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (AR)</label>
                        <textarea name="icerik_ar" class="form-control" rows="8">{{ old('icerik_ar') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (AR)</label>
                        <input type="text" name="seo_baslik_ar" class="form-control" value="{{ old('seo_baslik_ar') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (AR)</label>
                        <textarea name="seo_aciklama_ar" class="form-control" rows="2">{{ old('seo_aciklama_ar') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (AR)</label>
                        <input type="text" name="seo_anahtar_ar" class="form-control" value="{{ old('seo_anahtar_ar') }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label>Kapak Görseli</label>
                <input type="file" class="form-control-file" name="resim" accept="image/*">
            </div>

            <div class="form-group">
                <label>Durum</label>
                <select name="durum" class="form-control">
                    <option value="1" {{ old('durum', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('durum', 1) == 0 ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-light">İptal</a>
        </form>
    </div>
</div>

@endsection












