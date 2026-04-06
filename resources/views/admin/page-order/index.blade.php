@extends('layouts.admin')

@section('title', 'Sayfa Sırası')

@section('content')
<div class="admin-table-card" style="max-width:600px;">
    <div class="admin-table-header">
        <h5><i class="fas fa-sort" style="color:var(--admin-primary);margin-right:8px;"></i> Sayfa Bölüm Sırası</h5>
    </div>
    <div style="padding:24px;">
        <p style="font-size:14px;color:var(--admin-text-light);margin-bottom:20px;">Anasayfadaki bölümleri sürükleyip bırakarak sıralayın. Hero slider ve footer sabittir.</p>

        <div style="background:#f8fafc;border-radius:10px;padding:14px;margin-bottom:16px;font-size:13px;color:#64748b;border:1px solid #e2e8f0;">
            <i class="fas fa-lock" style="margin-right:6px;color:var(--admin-primary);"></i> <strong>Hero Slider</strong> — Her zaman en üstte
        </div>

        <ul id="sortableSections" style="list-style:none;padding:0;margin:0;">
            @foreach($order as $key)
                @if(isset($sections[$key]))
                <li data-key="{{ $key }}" style="background:#fff;border:2px solid #e2e8f0;border-radius:10px;padding:16px 18px;margin-bottom:10px;display:flex;align-items:center;gap:14px;cursor:grab;transition:all 0.2s;user-select:none;">
                    <i class="fas fa-grip-vertical" style="color:#cbd5e1;font-size:16px;"></i>
                    <div style="width:38px;height:38px;border-radius:8px;background:var(--admin-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">
                        <i class="fas {{ $sections[$key]['icon'] }}"></i>
                    </div>
                    <span style="font-size:15px;font-weight:600;color:var(--admin-dark);">{{ $sections[$key]['label'] }}</span>
                </li>
                @endif
            @endforeach
        </ul>

        <div style="background:#f8fafc;border-radius:10px;padding:14px;margin-top:16px;font-size:13px;color:#64748b;border:1px solid #e2e8f0;">
            <i class="fas fa-lock" style="margin-right:6px;color:var(--admin-primary);"></i> <strong>Footer</strong> — Her zaman en altta
        </div>

        <div id="saveStatus" style="margin-top:16px;font-size:14px;color:#10b981;display:none;">
            <i class="fas fa-check-circle"></i> Sıralama kaydedildi!
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function() {
    var el = document.getElementById('sortableSections');
    var status = document.getElementById('saveStatus');

    Sortable.create(el, {
        animation: 200,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            var order = [];
            el.querySelectorAll('li').forEach(function(li) {
                order.push(li.dataset.key);
            });

            fetch('{{ route("admin.page-order.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            })
            .then(function(r) { return r.json(); })
            .then(function() {
                status.style.display = 'block';
                setTimeout(function() { status.style.display = 'none'; }, 2000);
            });
        }
    });
})();
</script>
@endpush

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: var(--admin-primary) !important;
        border-color: var(--admin-primary) !important;
    }
    .sortable-ghost * { color: #fff !important; }
    .sortable-chosen {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        transform: scale(1.02);
    }
    #sortableSections li:hover {
        border-color: var(--admin-primary);
        background: #f8fafc;
    }
    #sortableSections li:active {
        cursor: grabbing;
    }
</style>
@endpush
@endsection
