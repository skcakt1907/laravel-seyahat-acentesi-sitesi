// Arama fonksiyonu
document.getElementById('aramaInput').addEventListener('keyup', function() {
  aramaFiltrele();
});

document.getElementById('durumFiltre').addEventListener('change', function() {
  aramaFiltrele();
});

function aramaFiltrele() {
  const arama = document.getElementById('aramaInput').value.toLowerCase();
  const durum = document.getElementById('durumFiltre').value;
  const table = document.getElementById('uyelerTable');
  const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
  
  let gorunenSayisi = 0;
  
  for (let i = 0; i < rows.length; i++) {
    const row = rows[i];
    const text = row.textContent || row.innerText;
    const durumCell = row.cells[4]?.querySelector('.badge')?.textContent || '';
    
    let goster = true;
    
    // Arama kontrolü
    if (arama && !text.toLowerCase().includes(arama)) {
      goster = false;
    }
    
    // Durum kontrolü
    if (durum !== '') {
      if (durum === '1' && !durumCell.includes('Aktif')) {
        goster = false;
      }
      if (durum === '0' && !durumCell.includes('Pasif')) {
        goster = false;
      }
    }
    
    row.style.display = goster ? '' : 'none';
    if (goster) gorunenSayisi++;
  }
}

function aramaTemizle() {
  document.getElementById('aramaInput').value = '';
  document.getElementById('durumFiltre').value = '';
  aramaFiltrele();
}

