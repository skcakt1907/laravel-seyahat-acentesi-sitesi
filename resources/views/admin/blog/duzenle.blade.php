@extends('layouts.admin')

@section('title', 'Blog Düzenle')
@section('page-title', 'Blog Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Blog Yazısını Düzenle</h6>
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

        <form action="{{ route('admin.blog.duzenlePost', $blog->id) }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $blog->baslik ?? $blog->adi ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Özet (TR)</label>
                        <textarea name="ozet" class="form-control" rows="3">{{ old('ozet', $blog->ozet ?? $blog->kisa ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (TR) *</label>
                        <textarea name="icerik" class="form-control" rows="8" required>{{ old('icerik', $blog->icerik ?? $blog->aciklama ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (TR)</label>
                        <input type="text" name="seo_baslik" class="form-control" value="{{ old('seo_baslik', $blog->seo_baslik ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (TR)</label>
                        <textarea name="seo_aciklama" class="form-control" rows="2">{{ old('seo_aciklama', $blog->seo_aciklama ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (TR)</label>
                        <input type="text" name="seo_anahtar" class="form-control" value="{{ old('seo_anahtar', $blog->seo_anahtar ?? '') }}">
                    </div>
                </div>

                {{-- İngilizce --}}
                <div class="tab-pane fade" id="tab-en" role="tabpanel">
                    <div class="form-group">
                        <label>Başlık (EN)</label>
                        <input type="text" name="baslik_en" class="form-control" value="{{ old('baslik_en', $blog->baslik_en ?? $blog->adi_en ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Özet (EN)</label>
                        <textarea name="ozet_en" class="form-control" rows="3">{{ old('ozet_en', $blog->ozet_en ?? $blog->kisa_en ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (EN)</label>
                        <textarea name="icerik_en" class="form-control" rows="8">{{ old('icerik_en', $blog->icerik_en ?? $blog->aciklama_en ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (EN)</label>
                        <input type="text" name="seo_baslik_en" class="form-control" value="{{ old('seo_baslik_en', $blog->seo_baslik_en ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (EN)</label>
                        <textarea name="seo_aciklama_en" class="form-control" rows="2">{{ old('seo_aciklama_en', $blog->seo_aciklama_en ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (EN)</label>
                        <input type="text" name="seo_anahtar_en" class="form-control" value="{{ old('seo_anahtar_en', $blog->seo_anahtar_en ?? '') }}">
                    </div>
                </div>

                {{-- Arapça --}}
                <div class="tab-pane fade" id="tab-ar" role="tabpanel">
                    <div class="form-group">
                        <label>Başlık (AR)</label>
                        <input type="text" name="baslik_ar" class="form-control" value="{{ old('baslik_ar', $blog->baslik_ar ?? $blog->adi_ar ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Özet (AR)</label>
                        <textarea name="ozet_ar" class="form-control" rows="3">{{ old('ozet_ar', $blog->ozet_ar ?? $blog->kisa_ar ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>İçerik (AR)</label>
                        <textarea name="icerik_ar" class="form-control" rows="8">{{ old('icerik_ar', $blog->icerik_ar ?? $blog->aciklama_ar ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Başlık (AR)</label>
                        <input type="text" name="seo_baslik_ar" class="form-control" value="{{ old('seo_baslik_ar', $blog->seo_baslik_ar ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>SEO Açıklama (AR)</label>
                        <textarea name="seo_aciklama_ar" class="form-control" rows="2">{{ old('seo_aciklama_ar', $blog->seo_aciklama_ar ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>SEO Anahtar Kelimeler (AR)</label>
                        <input type="text" name="seo_anahtar_ar" class="form-control" value="{{ old('seo_anahtar_ar', $blog->seo_anahtar_ar ?? '') }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label>Kapak Görseli</label>
                @if(!empty($blog->resim))
                    <div class="mb-2">
                        @php
                            $resimSrc = '';
                            if (!empty($blog->resim)) {
                                $resimSrc = str_contains($blog->resim, '/') ? asset($blog->resim) : asset('tema/uploads/bloglar/' . $blog->resim);
                            }
                        @endphp
                        <img src="{{ $resimSrc }}" alt="{{ $blog->baslik ?? $blog->adi ?? '' }}" style="max-width: 240px; max-height: 180px;" class="img-fluid">
                    </div>
                @endif
                <input type="file" class="form-control-file" name="resim" accept="image/*">
            </div>

            <div class="form-group">
                <label>Durum</label>
                <select name="durum" class="form-control">
                    <option value="1" {{ old('durum', $blog->durum) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('durum', $blog->durum) == 0 ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-light">İptal</a>
        </form>
    </div>
</div>

@endsection












