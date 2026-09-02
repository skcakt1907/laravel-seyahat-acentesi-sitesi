{{-- Arapca icin sagdan sola duzen --}}
@if(app()->getLocale() === 'ar')
<style>
body{direction:rtl;text-align:right}
.container,.row{direction:rtl}
.text-left{text-align:right!important}
.text-right{text-align:left!important}
.me-1,.me-2,.me-3{margin-right:0!important}
.ms-1,.ms-2,.ms-3{margin-left:0!important}
i.fas,i.far,i.fab{margin-left:6px;margin-right:0}
input,textarea,select{text-align:right;direction:rtl}
input[type="email"],input[type="tel"],input[type="number"]{direction:ltr;text-align:left}
</style>
@endif
