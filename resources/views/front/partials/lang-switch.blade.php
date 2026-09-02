{{-- Dil secici — bayrak + kod, aktif dil vurgulu --}}
@php
    $__diller = [
        'en' => ['ad' => 'English',    'bayrak' => '🇬🇧'],
        'de' => ['ad' => 'Deutsch',    'bayrak' => '🇩🇪'],
        'nl' => ['ad' => 'Nederlands', 'bayrak' => '🇳🇱'],
        'ru' => ['ad' => 'Русский',    'bayrak' => '🇷🇺'],
        'ar' => ['ad' => 'العربية',    'bayrak' => '🇸🇦'],
        'tr' => ['ad' => 'Türkçe',     'bayrak' => '🇹🇷'],
    ];
    $__aktif = app()->getLocale();
@endphp
<div class="lang-switch" id="langSwitch">
    <button type="button" class="lang-current" onclick="document.getElementById('langSwitch').classList.toggle('open')" aria-label="Language">
        <span class="lang-flag">{{ $__diller[$__aktif]['bayrak'] ?? '🌐' }}</span>
        <span class="lang-code">{{ strtoupper($__aktif) }}</span>
        <i class="fas fa-chevron-down" style="font-size:10px;opacity:.7"></i>
    </button>
    <div class="lang-menu">
        @foreach($__diller as $kod => $d)
            <a href="{{ url('/dil/'.$kod) }}" class="{{ $kod === $__aktif ? 'on' : '' }}" hreflang="{{ $kod }}">
                <span class="lang-flag">{{ $d['bayrak'] }}</span> {{ $d['ad'] }}
            </a>
        @endforeach
    </div>
</div>
<style>
.lang-switch{position:relative;display:inline-block;font-size:14px}
.lang-switch .lang-current{display:inline-flex;align-items:center;gap:6px;background:transparent;border:1px solid rgba(255,255,255,.28);
  color:inherit;padding:6px 10px;border-radius:8px;cursor:pointer;line-height:1;font:inherit}
.lang-switch .lang-current:hover{border-color:rgba(255,255,255,.6)}
.lang-switch .lang-flag{font-size:16px;line-height:1}
.lang-switch .lang-code{font-weight:700;letter-spacing:.03em}
.lang-switch .lang-menu{position:absolute;top:calc(100% + 8px);right:0;min-width:180px;background:#fff;border-radius:10px;
  box-shadow:0 12px 34px rgba(0,0,0,.18);padding:6px;opacity:0;visibility:hidden;transform:translateY(-6px);transition:.18s;z-index:1200}
.lang-switch.open .lang-menu{opacity:1;visibility:visible;transform:translateY(0)}
.lang-switch .lang-menu a{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;color:#1f2937;
  text-decoration:none;white-space:nowrap;font-size:14px}
.lang-switch .lang-menu a:hover{background:#f3f4f6}
.lang-switch .lang-menu a.on{background:#eef2ff;font-weight:700}
html[dir="rtl"] .lang-switch .lang-menu{right:auto;left:0}
</style>
<script>
document.addEventListener('click', function (e) {
    var s = document.getElementById('langSwitch');
    if (s && !s.contains(e.target)) s.classList.remove('open');
});
</script>
