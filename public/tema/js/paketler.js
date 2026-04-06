// Paketler Sayfası JavaScript
// Arama davranışı: mobilde sabit alt alta, desktop'ta toggle

document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('paketSearchToggle');
    var input = document.getElementById('paketSearchInput');

    if (!toggleBtn || !input) {
        return;
    }

    var isMobile = window.innerWidth <= 768;

    if (isMobile) {
        // MOBİL: input her zaman butonun altında ve tam genişlikte olsun
        input.style.display = 'block';
        input.style.maxWidth = '100%';
        input.style.flex = '1 1 100%';
        input.style.opacity = '1';
        input.style.width = '100%';
        input.style.color = '#fff';

        // Buton sadece input'u odaklasın, toggle yok
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            input.focus();
        });

        return;
    }

    // DESKTOP: Toggle davranışı
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();

        var isOpen = input.style.opacity === '1';

        if (!isOpen) {
            input.style.display = 'block';
            input.style.maxWidth = '260px';
            input.style.flex = '1 1 auto';
            input.style.opacity = '1';
            input.style.width = '100%';
            input.style.color = '#fff';
            setTimeout(function() {
                input.focus();
            }, 150);
        } else {
            input.value = '';
            input.style.maxWidth = '0';
            input.style.flex = '0 1 0';
            input.style.opacity = '0';
            input.style.width = '0';
        }
    });
});
