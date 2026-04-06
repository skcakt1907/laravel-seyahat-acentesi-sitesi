@extends('layouts.admin')

@section('title', 'Blog')
@section('page-title', 'Blog Yazıları')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Tüm Blog Yazıları</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bloglar as $blog)
                <tr>
                    <td>#{{ $blog->id }}</td>
                    <td>{{ $blog->baslik ?? $blog->adi }}</td>
                    <td>{{ $blog->tarih ?? $blog->created_at ?? '-' }}</td>
                    <td>
                        @if(isset($blog->durum) && $blog->durum == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Pasif</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.blog.duzenle', $blog->id) }}" class="btn btn-sm btn-outline-primary">
                            Düzenle
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Henüz blog yazısı yok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bloglar->hasPages())
    <div class="card-footer">
        {{ $bloglar->links() }}
    </div>
    @endif
</div>
@endsection
