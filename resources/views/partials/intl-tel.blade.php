<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
    .iti { width: 100%; }
    .iti__country-list { font-family: inherit; font-size: 14px; }
    .iti--separate-dial-code .iti__selected-flag { background-color: rgba(0,0,0,0.04); border-radius: 8px 0 0 8px; }
    .iti input[name="phone"] { padding-left: 96px !important; }
    .phone-error-msg { color:#dc2626; font-size:12px; margin-top:4px; display:none; }

    /* --- Telefon alanindaki form ikonu ---
       intl-tel bayrak kutusu bu ikonun uzerine biniyordu (bayrak bozuk gorunuyordu).
       Bayrak zaten ulkeyi gosterdigi icin ikon gizlenir. */
    .bh-input-group .iti ~ i,
    .bh-input-group i + .iti,
    .bh-input-group > i.fa-phone,
    .bh-input-group > i.fas.fa-phone { display: none !important; }

    /* Acilir ulke listesi her seyin ustunde kalsin.
       body'ye tasindigi icin .iti--container sarmalayicisini kullanir. */
    .iti--container { z-index: 99999 !important; }
    .iti__country-list { z-index: 99999 !important; }
    /* Form ikonlari listenin uzerine cikmasin (ikonda transform + z-index:2 vardi) */
    .bh-input-group > i { z-index: 1 !important; }

    /* --- Bayrak - kod cakismasi duzeltmesi ---
       Bayrak, ulke kodu ve ok dar alanda ust uste biniyordu.
       Ogeler yan yana hizalanir, aralarina nefes payi konur. */
    .iti--separate-dial-code .iti__selected-flag {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 0 9px 0 11px;
        box-sizing: border-box;
    }
    .iti--separate-dial-code .iti__selected-flag .iti__flag {
        flex: 0 0 auto;
        margin: 0;
    }
    .iti--separate-dial-code .iti__selected-dial-code {
        flex: 0 0 auto;
        margin: 0;
        font-weight: 600;
        white-space: nowrap;
    }
    .iti--separate-dial-code .iti__arrow {
        flex: 0 0 auto;
        margin: 0;
    }
    /* Yazi alani bayrak kutusunun altina kaymasin */
    .iti--separate-dial-code input[name="phone"] { padding-left: 104px !important; }





    /* Dark mode */
    [data-theme="dark"] .iti input[name="phone"],
    .dark-mode .iti input[name="phone"] {
        background-color: #1a2332 !important;
        color: #e2e8f0 !important;
        border-color: #2a3548 !important;
    }
    [data-theme="dark"] .iti--separate-dial-code .iti__selected-flag,
    .dark-mode .iti--separate-dial-code .iti__selected-flag {
        background-color: #243044 !important;
    }
    [data-theme="dark"] .iti--separate-dial-code .iti__selected-flag:hover,
    [data-theme="dark"] .iti--separate-dial-code .iti__selected-flag:focus,
    .dark-mode .iti--separate-dial-code .iti__selected-flag:hover,
    .dark-mode .iti--separate-dial-code .iti__selected-flag:focus {
        background-color: #2d3a52 !important;
    }
    [data-theme="dark"] .iti__country-list,
    .dark-mode .iti__country-list {
        background: #1a2332;
        border: 1px solid #2a3548;
        color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(0,0,0,0.5);
    }
    [data-theme="dark"] .iti__country,
    .dark-mode .iti__country {
        color: #e2e8f0;
    }
    [data-theme="dark"] .iti__country.iti__highlight,
    [data-theme="dark"] .iti__country:hover,
    .dark-mode .iti__country.iti__highlight,
    .dark-mode .iti__country:hover {
        background-color: rgba(0,102,204,0.25);
        color: #fff;
    }
    [data-theme="dark"] .iti__divider,
    .dark-mode .iti__divider {
        border-bottom-color: #2a3548;
    }
    [data-theme="dark"] .iti__dial-code,
    .dark-mode .iti__dial-code {
        color: #94a3b8;
    }
    [data-theme="dark"] .iti__selected-dial-code,
    .dark-mode .iti__selected-dial-code {
        color: #e2e8f0;
    }
    [data-theme="dark"] .iti__arrow,
    .dark-mode .iti__arrow {
        border-top-color: #94a3b8;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
(function(){
    function init() {
        document.querySelectorAll('input[name="phone"]').forEach(function(input){
            if (input.dataset.itiInited) return;
            input.dataset.itiInited = '1';

            // Bayrak kutusuyla cakismasin diye alandaki telefon ikonunu kaldir
            var kap = input.closest('.bh-input-group') || input.parentElement;
            if (kap) {
                var ikon = kap.querySelector(':scope > i.fa-phone, :scope > i.fas.fa-phone, :scope > i');
                if (ikon && !ikon.closest('.iti')) { ikon.remove(); }
            }

            var iti = window.intlTelInput(input, {
                initialCountry: 'gb',
                separateDialCode: true,
                // Liste body'ye tasinir: ust kapsayicidaki overflow:hidden kesemez,
                // form ikonlari da uzerine binemez
                dropdownContainer: document.body,
                autoPlaceholder: 'polite',
                nationalMode: false,
                preferredCountries: ['gb','tr','de','nl','fr','us'],
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js'
            });

            var existing = input.value.trim();
            if (existing && (existing.indexOf('+') === 0 || /^\d{10,}$/.test(existing))) {
                var apply = function(){ iti.setNumber(existing); };
                input.addEventListener('countrychange', function(){}, { once: true });
                if (window.intlTelInputUtils) apply();
                else input.addEventListener('open:countrydropdown', apply, { once: true });
                setTimeout(apply, 400);
            }

            var errMsg = document.createElement('div');
            errMsg.className = 'phone-error-msg';
            errMsg.textContent = 'Please enter a valid phone number with country code.';
            input.parentNode.parentNode.appendChild(errMsg);

            var form = input.closest('form');
            if (!form) return;

            form.addEventListener('submit', function(e){
                errMsg.style.display = 'none';
                input.style.borderColor = '';
                if (input.value.trim() === '') return;
                if (!iti.isValidNumber()) {
                    e.preventDefault();
                    errMsg.style.display = 'block';
                    input.style.borderColor = '#dc2626';
                    input.focus();
                    return false;
                }
                input.value = iti.getNumber();
            });

            input.addEventListener('input', function(){
                if (input.value.trim() !== '' && iti.isValidNumber()) {
                    input.style.borderColor = '';
                    errMsg.style.display = 'none';
                }
            });
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
