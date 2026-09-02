// Master Layout JavaScript
// Scroll header, dropdown, domain sorgulama, dil/para birimi değiştirme

// Google Translate - Sayfa yüklenmeden önce tarayıcı dilini kontrol et ve otomatik çevir
(function() {
  // Eğer zaten Google Translate aktifse, hiçbir şey yapma
  if (window.location.hash && window.location.hash.indexOf('#googtrans') !== -1) {
    return;
  }
  
  // Tarayıcı dilini algıla
  var browserLang = navigator.language || navigator.userLanguage;
  var langCode = browserLang.split('-')[0].toLowerCase();
  
  // Desteklenen diller ve Google Translate kodları
  var langMap = {
    'tr': 'tr',
    'en': 'en',
    'ar': 'ar',
    'de': 'de',
    'fr': 'fr',
    'es': 'es',
    'it': 'it',
    'ru': 'ru',
    'zh': 'zh-CN',
    'ja': 'ja',
    'ko': 'ko'
  };
  
  // Eğer tarayıcı dili desteklenen dillerden biri ise ve Türkçe değilse
  if (langMap[langCode] && langCode !== 'tr') {
    // URL hash'ini ayarla ve sayfayı yeniden yükle
    window.location.hash = '#googtrans(tr|' + langMap[langCode] + ')';
    // Sayfa yenileme sadece ilk yüklemede olsun
    if (!sessionStorage.getItem('googleTranslateAuto')) {
      sessionStorage.setItem('googleTranslateAuto', 'true');
      window.location.reload();
    }
  }
})();

// Google Translate Element Init - Desktop ve Mobile için
function googleTranslateElementInit() {
  // Desktop için
  if (document.getElementById('google_translate_element')) {
    new google.translate.TranslateElement({
      pageLanguage: 'tr',
      includedLanguages: 'tr,en,ar,de,fr,es,it,ru,zh-CN,ja,ko',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false,
      multilanguagePage: true
    }, 'google_translate_element');
  }
  
  // Mobile için
  if (document.getElementById('google_translate_element_mobile')) {
    new google.translate.TranslateElement({
      pageLanguage: 'tr',
      includedLanguages: 'tr,en,ar,de,fr,es,it,ru,zh-CN,ja,ko',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false,
      multilanguagePage: true
    }, 'google_translate_element_mobile');
  }
  
  // Sayfa yüklendiğinde select box'ı doğru dile ayarla
  setTimeout(function() {
    var hash = window.location.hash;
    if (hash && hash.indexOf('#googtrans') !== -1) {
      var match = hash.match(/#googtrans\(tr\|([^)]+)\)/);
      if (match && match[1]) {
        var targetLang = match[1];
        var combo = document.querySelector('.goog-te-combo');
        if (combo) {
          combo.value = targetLang;
          combo.dispatchEvent(new Event('change'));
        }
      }
    }
  }, 1000);
}

// Template değişkenlerini meta tag'lerden al
const changeCurrencyMeta = document.querySelector('meta[name="change-currency-url"]');
if (changeCurrencyMeta) {
  window.changeCurrencyUrlTemplate = changeCurrencyMeta.content;
}

const domainSorgulaMeta = document.querySelector('meta[name="domain-sorgula-url"]');
const sepetDomainMeta = document.querySelector('meta[name="sepet-domain-url"]');
if (domainSorgulaMeta && $('#domainForm').length) {
  $('#domainForm').data('url', domainSorgulaMeta.content);
}
if (sepetDomainMeta && $('#domainForm').length) {
  $('#domainForm').data('sepet-url', sepetDomainMeta.content);
}

// Scroll yapınca header'a koyu arka plan ekle
$(window).scroll(function() {
  if ($(this).scrollTop() > 100) {
    $('#header').addClass('scrolled');
  } else {
    $('#header').removeClass('scrolled');
  }
});

// Dil ve Para Birimi Dropdown Script
$(document).ready(function() {
  // Dropdown toggle
  $('.top-nav .dropdown-toggle').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $dropdown = $(this).parent('.dropdown');
    var isOpen = $dropdown.hasClass('open');
    
    // Tüm dropdownları kapat
    $('.top-nav .dropdown').removeClass('open');
    
    // Bu dropdown'ı aç
    if (!isOpen) {
      $dropdown.addClass('open');
    }
  });
  
  // Dropdown dışına tıklandığında kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.top-nav .dropdown').length) {
      $('.top-nav .dropdown').removeClass('open');
    }
  });
  
  // Para birimi ve dil değiştirme (desktop üst bar)
  $('.top-nav .dropdown-menu a').on('click', function(e) {
    e.preventDefault();
    
    var href = $(this).attr('href');
    
    // Mevcut URL
    var url = new URLSearchParams(window.location.search);
    
    // Yeni parametreleri parse et
    var newParams = href.split('?')[1];
    if (newParams) {
      var params = new URLSearchParams(newParams);
      
      // Currency parametresi varsa ekle/güncelle
      if (params.has('currency')) {
        url.set('currency', params.get('currency'));
      }
      
      // Lang parametresi varsa ekle/güncelle  
      if (params.has('lang')) {
        url.set('lang', params.get('lang'));
      }
    }
    
    // Dropdown'ı kapat
    $('.top-nav .dropdown').removeClass('open');
    
    // Yeni URL oluştur
    var newUrl = window.location.pathname + '?' + url.toString();
    
    // Sayfayı yönlendir
    window.location.href = newUrl;
  });

  // === Mobile navbar para birimi & dil dropdown ===
  $(document).on('click', '.mobile-nav-dropdown-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var $parent = $(this).closest('.mobile-nav-dropdown');
    var isOpen = $parent.hasClass('open');

    // Diğer açık dropdownları kapat
    $('.mobile-nav-dropdown').removeClass('open');

    // Bu dropdown'ı aç/kapat
    if (!isOpen) {
      $parent.addClass('open');
    }
  });

  // Mobile navbar dropdown dışına tıklanınca kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.mobile-nav-dropdown').length) {
      $('.mobile-nav-dropdown').removeClass('open');
    }
  });
  
  // Domain Sorgulama
  $('#domainForm').on('submit', function(e) {
    e.preventDefault();
    
    var alanadi = $('#alanadi').val().trim().toLowerCase();
    var uzanti = $('#uzanti').val();
    var submitBtn = $('#domainSorgula');
    
    if (!alanadi) {
      alert('Lütfen bir alan adı girin.');
      return;
    }
    
    // Butonu devre dışı bırak
    var originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Sorgulanıyor...');
    
    var domainSorgulaUrl = $('#domainForm').data('url') || '/domain/sorgula';
    var sepetUrl = $('#domainForm').data('sepet-url') || '/sepet/domain-ekle';
    
    $.ajax({
      url: domainSorgulaUrl,
      method: 'POST',
      data: {
        alanadi: alanadi,
        uzanti: uzanti,
        _token: $('input[name="_token"]').val()
      },
      success: function(response) {
        if (response.success) {
          var html = '<div class="domain-results mt-4">';
          
          response.results.forEach(function(item) {
            var durumClass = item.musait ? 'success' : 'danger';
            var durumText = item.musait ? 'MÜSAİT' : 'KAYITLI';
            var icon = item.musait ? '✓' : '✗';
            
            html += '<div class="alert alert-' + durumClass + ' d-flex justify-content-between align-items-center">';
            html += '<div><strong>' + icon + ' ' + item.domain + '</strong></div>';
            html += '<div class="d-flex align-items-center">';
            html += '<span class="badge badge-' + durumClass + '">' + durumText + '</span> ';
            
            if (item.musait) {
              if (item.kayit_fiyat > 0) {
                html += '<span class="text-dark ml-3 mr-3"><strong>' + item.kayit_fiyat + ' TL</strong></span>';
              }
              // AJAX ile sepete ekle (GET yerine POST)
              html += '<button type="button" class="btn btn-sm btn-primary ml-2 add-domain-to-cart" data-domain="' + encodeURIComponent(item.domain) + '" data-price="' + item.kayit_fiyat + '">';
              html += '<i class="fas fa-shopping-cart"></i> Satın Al';
              html += '</button>';
            }
            
            html += '</div>';
            html += '</div>';
          });
          
          html += '</div>';
          $('#domainBilgileri').html(html);
        } else {
          $('#domainBilgileri').html('<div class="alert alert-danger">' + response.message + '</div>');
        }
      },
      error: function(xhr, status, error) {
        var errorMsg = 'Bir hata oluştu. Lütfen tekrar deneyin.';
        try {
          var response = JSON.parse(xhr.responseText);
          if (response.message) {
            errorMsg = response.message;
          }
        } catch(e) {}
        
        $('#domainBilgileri').html('<div class="alert alert-danger">' + errorMsg + '</div>');
      },
      complete: function() {
        // Butonu tekrar aktif et
        submitBtn.prop('disabled', false).text(originalText);
      }
    });
  });
});

// Dil değiştirme fonksiyonu
function changeLang(lang) {
  try {
    var url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
  } catch (e) {
    var hashIndex = window.location.href.indexOf('#');
    var base = hashIndex > -1 ? window.location.href.slice(0, hashIndex) : window.location.href;
    if (base.indexOf('?') === -1) {
      window.location.href = base + '?lang=' + lang;
    } else if (base.indexOf('lang=') === -1) {
      window.location.href = base + '&lang=' + lang;
    } else {
      window.location.href = base.replace(/lang=([^&]*)/, 'lang=' + lang);
    }
  }
}

// Para birimi değiştirme fonksiyonu
function changeCurrency(currency) {
  var template = window.changeCurrencyUrlTemplate || '/change-currency/__currency__';
  window.location.href = template.replace('__currency__', currency);
}

// Lazy loading için background image yükleme
$(document).ready(function() {
  if ('IntersectionObserver' in window) {
    var lazyBgObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var $el = $(entry.target);
          var bgUrl = $el.data('bg');
          if (bgUrl) {
            var img = new Image();
            img.onload = function() {
              $el.css('--bg-image', 'url(' + bgUrl + ')');
              $el.addClass('loaded');
            };
            img.src = bgUrl;
          }
          observer.unobserve(entry.target);
        }
      });
    }, {
      rootMargin: '50px'
    });
    
    document.querySelectorAll('.lazy-bg').forEach(function(el) {
      lazyBgObserver.observe(el);
    });
  } else {
    // Fallback: IntersectionObserver desteklenmiyorsa hemen yükle
    $('.lazy-bg').each(function() {
      var $el = $(this);
      var bgUrl = $el.data('bg');
      if (bgUrl) {
        var img = new Image();
        img.onload = function() {
          $el.css('--bg-image', 'url(' + bgUrl + ')');
          $el.addClass('loaded');
        };
        img.src = bgUrl;
      }
    });
  }
  
  // Mobile Navbar Toggle
  $('.mobile-navbar-toggle').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    $(this).toggleClass('active');
    $('.mobile-menu').toggleClass('active');
    $('.mobile-menu-overlay').toggleClass('active');
    
    // Body scroll'u engelle/aktif et
    if ($(this).hasClass('active')) {
      $('body').css('overflow', 'hidden');
      $('.mobile-menu-overlay').show();
    } else {
      $('body').css('overflow', '');
      $('.mobile-menu-overlay').hide();
    }
  });
  
  // Overlay tıklanınca menüyü kapat
  $('.mobile-menu-overlay').on('click', function() {
    $('.mobile-navbar-toggle').removeClass('active');
    $('.mobile-menu').removeClass('active');
    $('.mobile-menu-overlay').removeClass('active').hide();
    $('body').css('overflow', '');
  });
  
  // Mobile Hamburger Menu Toggle (Desktop için)
  $('.nav-menu .menu-toggle').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    $(this).toggleClass('active');
    $('.nav-menu .main-menu').toggleClass('active');
    
    // Body scroll'u engelle/aktif et
    if ($(this).hasClass('active')) {
      $('body').css('overflow', 'hidden');
    } else {
      $('body').css('overflow', '');
    }
  });

  // Menü dışına tıklandığında kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.nav-menu').length) {
      $('.nav-menu .menu-toggle').removeClass('active');
      $('.nav-menu .main-menu').removeClass('active');
      $('body').css('overflow', '');
    }
  });

  // Alt menü toggle (mobile)
  $(document).on('click', '.nav-menu .menu-item-has-children > a', function(e) {
    if ($(window).width() <= 991) {
      e.preventDefault();
      $(this).parent('.menu-item-has-children').toggleClass('active');
    }
  });
  
  // Mobil menü dropdown toggle
  $(document).on('click', '.mobile-menu-toggle', function(e) {
    e.preventDefault();
    $(this).closest('.mobile-menu-dropdown').toggleClass('active');
  });

  // Event delegation ile sepete ekle butonunu dinle (master-layout.js'den gelen butonlar için)
  $(document).on('click', '.add-domain-to-cart', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $btn = $(this);
    var domain = $btn.data('domain');
    var price = parseFloat($btn.data('price') || 0);
    
    if (!domain || !price) {
      console.error('Domain veya fiyat bilgisi eksik');
      return;
    }
    
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Ekleniyor...');
    
    $.ajax({
      url: '/sepet/domain-ekle',
      method: 'POST',
      data: {
        domain: domain,
        fiyat: price,
        _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      success: function(response) {
        if (response.success) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              title: 'Başarılı',
              text: response.message || 'Domain sepete eklendi!',
              timer: 2000,
              showConfirmButton: false
            }).then(function() {
              window.location.href = '/sepet';
            });
          } else {
            alert('Domain sepete eklendi!');
            window.location.href = '/sepet';
          }
        } else {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Hata',
              text: response.message || 'Sepete eklenirken hata oluştu'
            });
          } else {
            alert(response.message || 'Sepete eklenirken hata oluştu');
          }
          $btn.prop('disabled', false).html('<i class="fas fa-shopping-cart"></i> Satın Al');
          
          if (response.redirect) {
            setTimeout(function() {
              window.location.href = response.redirect;
            }, 2000);
          }
        }
      },
      error: function(xhr) {
        console.error('Sepete ekleme hatası:', xhr);
        var errorMessage = 'Sepete eklenirken hata oluştu';
        
        if (xhr.status === 401) {
          errorMessage = 'Sepete ürün eklemek için giriş yapmalısınız.';
          try {
            var response = JSON.parse(xhr.responseText);
            if (response.redirect) {
              setTimeout(function() {
                window.location.href = response.redirect;
              }, 2000);
            }
          } catch(e) {}
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Hata',
            text: errorMessage
          });
        } else {
          alert(errorMessage);
        }
        $btn.prop('disabled', false).html('<i class="fas fa-shopping-cart"></i> Satın Al');
      }
    });
  });
});

// Google Translate Integration
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'tr',
    includedLanguages: 'tr,en,ar',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    autoDisplay: false
  }, 'google_translate_element_mobile');
}

// Manuel dil seçimi (lang=query) ile Google Translate'i otomatik tetikle
document.addEventListener('DOMContentLoaded', function () {
  function applyGoogleTranslate(lang) {
    var interval = setInterval(function () {
      var combo = document.querySelector('.goog-te-combo');
      if (combo) {
        combo.value = lang;
        combo.dispatchEvent(new Event('change'));
        clearInterval(interval);
      }
    }, 300);

    // 10 saniye sonra denemeyi bırak
    setTimeout(function () {
      clearInterval(interval);
    }, 10000);
  }

  var params = new URLSearchParams(window.location.search);
  var lang = params.get('lang');
  if (lang === 'en' || lang === 'ar') {
    applyGoogleTranslate(lang);
  }
});

// Dropdown menüleri çalıştır
$(document).ready(function() {
  // Dropdown toggle
  $(document).on('click', '.dropdown-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var $parent = $(this).parent('.dropdown');
    var isOpen = $parent.hasClass('open');
    
    // Tüm dropdown'ları kapat
    $('.dropdown').removeClass('open');
    
    // Tıklananı aç/kapat
    if (!isOpen) {
      $parent.addClass('open');
    }
  });
  
  // Sayfa tıklanınca kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.dropdown').length) {
      $('.dropdown').removeClass('open');
    }
  });
});

// Lazy Loading Script
// Native lazy loading fallback for older browsers
if ('loading' in HTMLImageElement.prototype) {
  const images = document.querySelectorAll('img[loading="lazy"]');
  images.forEach(img => {
    if (img.dataset.src) {
      img.src = img.dataset.src;
    }
  });
} else {
  // Fallback for older browsers
  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js';
  script.onload = function() {
    const observer = lozad('.lazy-load', {
      loaded: function(el) {
        el.classList.add('loaded');
      }
    });
    observer.observe();
  };
  document.body.appendChild(script);
}

// Preload critical resources
const preloadLink = document.createElement('link');
preloadLink.rel = 'preload';
preloadLink.as = 'style';
preloadLink.href = document.querySelector('link[href*="style.css"]')?.href || '/tema/css/style.css';
document.head.appendChild(preloadLink);

// Google çeviri barı çıktığında body'ye sınıf ekle
function adjustForGoogleTranslateBanner() {
  var banner = document.querySelector('.goog-te-banner-frame.skiptranslate');
  if (banner && banner.offsetHeight > 0) {
    document.body.classList.add('google-translate-active');
  } else {
    document.body.classList.remove('google-translate-active');
  }
}

window.addEventListener('load', function () {
  setTimeout(adjustForGoogleTranslateBanner, 1500);
});
window.addEventListener('resize', adjustForGoogleTranslateBanner);
