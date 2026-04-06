const aramaInput = document.getElementById('aramaInput');
const kategoriFiltre = document.getElementById('kategoriFiltre');
const durumFiltre = document.getElementById('durumFiltre');

if (aramaInput) {
  aramaInput.addEventListener('keyup', aramaFiltrele);
}
if (kategoriFiltre) {
  kategoriFiltre.addEventListener('change', aramaFiltrele);
}
if (durumFiltre) {
  durumFiltre.addEventListener('change', aramaFiltrele);
}

function aramaFiltrele() {
  const arama = (aramaInput?.value || '').toLowerCase();
  const durum = durumFiltre?.value ?? '';
  const table = document.getElementById('paketlerTable');
  const rows = table?.querySelectorAll('tbody tr') ?? [];

  rows.forEach(function(row) {
    const text = row.textContent || row.innerText;
    const durumCell = row.cells[3]?.querySelector('.badge')?.textContent || '';

    let goster = true;

    if (arama && !text.toLowerCase().includes(arama)) {
      goster = false;
    }

    if (durum !== '') {
      if (durum === '1' && !durumCell.includes('Aktif')) {
        goster = false;
      }
      if (durum === '0' && !durumCell.includes('Pasif')) {
        goster = false;
      }
    }

    row.style.display = goster ? '' : 'none';
  });
}

function aramaTemizle() {
  if (aramaInput) aramaInput.value = '';
  if (kategoriFiltre) kategoriFiltre.value = '';
  if (durumFiltre) durumFiltre.value = '';
  aramaFiltrele();
}

