{{-- Icerik cevirisi kutulari.
     $kayit   : duzenlenen satir (ceviri JSON kolonunu icerir)
     $alanlar : ['title' => ['etiket' => 'Başlık', 'tip' => 'input'], ...]      --}}
@php
    $__ceviri = $kayit->ceviri ?? null;
    $__ceviri = is_string($__ceviri) ? (json_decode($__ceviri, true) ?: []) : (array) ($__ceviri ?: []);
    $__diller = ic_diller();
@endphp

<div class="mb-3" style="border:1px solid #e5e7eb;border-radius:10px;padding:14px;background:#fafafa">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap">
        <strong style="font-size:14px">🌐 Diğer Diller</strong>
        <span style="font-size:12px;color:#6b7280">Boş bıraktığınız alanda İngilizce metin gösterilir.</span>
    </div>

    <ul class="nav nav-tabs" role="tablist" style="margin-bottom:12px">
        @foreach($__diller as $kod => $ad)
            <li class="nav-item">
                <button class="nav-link @if($loop->first) active @endif" type="button"
                        data-bs-toggle="tab" data-bs-target="#cev-{{ $kod }}">{{ $ad }}</button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($__diller as $kod => $ad)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="cev-{{ $kod }}">
                @foreach($alanlar as $alan => $bilgi)
                    @php $__deger = old('ceviri.'.$kod.'.'.$alan, $__ceviri[$kod][$alan] ?? ''); @endphp
                    <div class="mb-2">
                        <label class="settings-label">{{ $bilgi['etiket'] }} ({{ $ad }})</label>
                        @if(($bilgi['tip'] ?? 'input') === 'textarea')
                            <textarea name="ceviri[{{ $kod }}][{{ $alan }}]" class="settings-input"
                                      rows="4" style="height:auto;"
                                      dir="{{ $kod === 'ar' ? 'rtl' : 'ltr' }}">{{ $__deger }}</textarea>
                        @else
                            <input type="text" name="ceviri[{{ $kod }}][{{ $alan }}]" class="settings-input"
                                   value="{{ $__deger }}" dir="{{ $kod === 'ar' ? 'rtl' : 'ltr' }}">
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
