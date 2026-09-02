@if(!empty($ayar->google_analytics))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ayar->google_analytics }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '{{ $ayar->google_analytics }}');
</script>
@endif
