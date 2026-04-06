// Yöneticiler form - Üye seçimi ve otomatik doldurma
(function () {
  const uyeSelect = document.getElementById('uye_id_select');
  const kullaniciInput = document.getElementById('kullaniciadi');
  const emailInput = document.getElementById('email');
  const adInput = document.getElementById('adi');
  const telefonInput = document.getElementById('telefon');

  function applySelection(option) {
    if (!option || !option.value) {
      emailInput.value = '';
      adInput.value = '';
      telefonInput.value = '';
      if (!kullaniciInput.dataset.manual) {
        kullaniciInput.value = '';
      }
      return;
    }

    const email = option.getAttribute('data-email') || '';
    const ad = option.getAttribute('data-ad') || '';
    const telefon = option.getAttribute('data-telefon') || '';

    emailInput.value = email;
    adInput.value = ad;
    telefonInput.value = telefon;

    if (!kullaniciInput.dataset.manual) {
      const suggestion = email ? email.split('@')[0] : ('uye' + option.value);
      kullaniciInput.value = suggestion;
    }
  }

  if (uyeSelect) {
    uyeSelect.addEventListener('change', function () {
      applySelection(this.selectedOptions[0]);
    });

    if (uyeSelect.value) {
      applySelection(uyeSelect.selectedOptions[0]);
    }
  }

  if (kullaniciInput) {
    kullaniciInput.addEventListener('input', function () {
      if (this.value.trim().length) {
        this.dataset.manual = '1';
      } else {
        delete this.dataset.manual;
      }
    });
  }
})();

