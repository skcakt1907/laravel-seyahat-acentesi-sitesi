{{-- Fiyatlar GBP tutulur; JS ile hesaplanan tutarlar da ayni kurla gosterilsin --}}
@php
    $__cur = [
        'code'      => \App\Helpers\SiteCurrency::code(),
        'symbol'    => \App\Helpers\SiteCurrency::symbol(),
        'rate'      => round(\App\Helpers\SiteCurrency::convert(1.0), 6),
        'converted' => \App\Helpers\SiteCurrency::isConverted(),
        'locale'    => app()->getLocale(),
    ];
@endphp
<script>
window.__CUR = {!! json_encode($__cur, JSON_UNESCAPED_UNICODE) !!};
window.__fiyatGoster = function (gbp) {
    var c = window.__CUR, n = parseFloat(gbp) || 0;
    if (!n) return '';
    if (!c.converted) return c.symbol + Math.round(n);
    var v = Math.ceil(n * c.rate);
    var bicim = { ru: 'ru-RU', de: 'de-DE', nl: 'nl-NL', ar: 'en-US' }[c.locale] || 'en-GB';
    return '≈ ' + c.symbol + v.toLocaleString(bicim);
};
</script>
