// Navbar dropdown'ları birbirine girmesin diye - Geliştirilmiş versiyon
$(document).ready(function() {
  // Tüm dropdown'ları kapat
  function closeAllDropdowns(exceptId) {
    $('.navbar-nav-right .nav-item.dropdown').each(function() {
      var $dropdown = $(this);
      var dropdownId = $dropdown.attr('id');
      if (dropdownId !== exceptId) {
        $dropdown.removeClass('show');
        $dropdown.find('.dropdown-menu').removeClass('show');
        $dropdown.find('.dropdown-toggle').removeClass('show').attr('aria-expanded', 'false');
      }
    });
    // Custom language dropdown'ı da kapat
    if (exceptId !== 'languageDropdownWrapper') {
      $('#languageDropdownMenu').hide();
    }

    // Custom currency dropdown'ı da kapat
    if (exceptId !== 'currencyDropdownWrapper') {
      $('#currencyDropdownMenu').hide();
    }
  }

  // Custom dil dropdown toggle
  window.toggleLanguageMenu = function(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    var $menu = $('#languageDropdownMenu');
    var isVisible = $menu.is(':visible');
    
    // Tüm dropdown'ları kapat
    closeAllDropdowns(null);
    $('#profileDropdownWrapper').removeClass('show');
    $('#profileDropdownMenu').hide();
    
    // Dil dropdown'ını toggle et
    if (isVisible) {
      $menu.hide();
    } else {
      $menu.show();
    }
  };

  // Custom para birimi dropdown toggle
  window.toggleCurrencyMenu = function(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    var $menu = $('#currencyDropdownMenu');
    var isVisible = $menu.is(':visible');

    // Tüm dropdown'ları kapat
    closeAllDropdowns(null);
    $('#profileDropdownWrapper').removeClass('show');
    $('#profileDropdownMenu').hide();

    // Currency dropdown'ını toggle et
    if (isVisible) {
      $menu.hide();
    } else {
      $menu.show();
    }
  };

  // Profile dropdown toggle - açıksa kapat, kapalıysa aç
  $('#profileDropdown').on('click', function(e) {
    e.stopPropagation();
    var $dropdown = $('#profileDropdownWrapper');
    var isOpen = $dropdown.hasClass('show');
    
    if (isOpen) {
      // Açıksa kapat
      $dropdown.removeClass('show');
      $('#profileDropdownMenu').removeClass('show').hide();
      $(this).attr('aria-expanded', 'false');
    } else {
      // Kapalıysa aç ve diğerlerini kapat
    closeAllDropdowns('profileDropdownWrapper');
      $('#languageDropdownMenu').hide();
      $('#currencyDropdownMenu').hide();
      $dropdown.addClass('show');
      $('#profileDropdownMenu').addClass('show').show();
      $(this).attr('aria-expanded', 'true');
    }
  });

  // Dışarı tıklandığında tüm dropdown'ları kapat
  $(document).on('click', function(e) {
    var $target = $(e.target);
    var isInsideDropdown = $target.closest('.navbar-nav-right .nav-item.dropdown').length > 0;
    var isInsideCustomLanguageDropdown = $target.closest('#languageDropdownWrapper').length > 0;
    var isInsideCustomCurrencyDropdown = $target.closest('#currencyDropdownWrapper').length > 0;
    var isDropdownToggle = $target.closest('.dropdown-toggle').length > 0;
    var isLanguageToggle = $target.closest('#languageDropdown').length > 0;
    var isCurrencyToggle = $target.closest('#currencyDropdown').length > 0;

    if (!isInsideDropdown && !isInsideCustomLanguageDropdown && !isInsideCustomCurrencyDropdown && !isDropdownToggle && !isLanguageToggle && !isCurrencyToggle) {
      closeAllDropdowns(null);
      $('#profileDropdownWrapper').removeClass('show');
      $('#profileDropdownMenu').removeClass('show').hide();
      $('#languageDropdownMenu').hide();
      $('#currencyDropdownMenu').hide();
    }
  });

  // ESC tuşu ile kapat
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
      closeAllDropdowns(null);
      $('#profileDropdownWrapper').removeClass('show');
      $('#profileDropdownMenu').removeClass('show').hide();
      $('#languageDropdownMenu').hide();
      $('#currencyDropdownMenu').hide();
    }
  });

  // Agresif yaklaşım: Her frame'de kontrol et
  setInterval(function() {
    if ($('#languageDropdownWrapper').hasClass('show') || $('#languageDropdownMenu').is(':visible')) {
      var $menu = $('#languageDropdownMenu');
      if ($menu.length && $menu.is(':visible')) {
        $menu.attr('style', 'right: auto !important; left: 0 !important; transform: translateX(-100%) !important; position: absolute !important; top: 100% !important;');
      }
    }

    if ($('#currencyDropdownWrapper').hasClass('show') || $('#currencyDropdownMenu').is(':visible')) {
      var $menu2 = $('#currencyDropdownMenu');
      if ($menu2.length && $menu2.is(':visible')) {
        $menu2.attr('style', 'right: auto !important; left: 0 !important; transform: translateX(-100%) !important; position: absolute !important; top: 100% !important;');
      }
    }
  }, 50);
  
  // Herhangi bir dropdown açıldığında diğerlerini kapat
  $('.navbar-nav-right .nav-item.dropdown').on('shown.bs.dropdown', function() {
    var $current = $(this);
    var currentId = $current.attr('id');
    closeAllDropdowns(currentId);
    $('#languageDropdownMenu').hide();
    $('#currencyDropdownMenu').hide();
  });
  
  // Dışarı tıklandığında dil dropdown'ını kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('#languageDropdownWrapper').length && !$(e.target).closest('#languageDropdown').length) {
      $('#languageDropdownMenu').hide();
    }
  });

  // Dışarı tıklandığında currency dropdown'ını kapat
  $(document).on('click', function(e) {
    if (!$(e.target).closest('#currencyDropdownWrapper').length && !$(e.target).closest('#currencyDropdown').length) {
      $('#currencyDropdownMenu').hide();
    }
  });
  
  // Sidebar collapse menülerini basit toggle yap - sadece aç/kapa, diğerlerini kapatma
  // Bootstrap'in kendi collapse mekanizmasını devre dışı bırak
  $('.sidebar .nav-link[data-toggle="collapse"]').each(function() {
    var $link = $(this);
    // data-toggle'ı kaldır, kendi toggle'ımızı kullanacağız
    $link.removeAttr('data-toggle');
    $link.attr('data-sidebar-toggle', 'true');
  });
  
  // Basit toggle event listener
  $(document).on('click', '.sidebar .nav-link[data-sidebar-toggle="true"]', function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    
    var $this = $(this);
    var target = $this.attr('href');
    
    if (!target || target.charAt(0) !== '#') {
      return false;
    }
    
    var $targetCollapse = $(target);

    if (!$targetCollapse.length) {
      return false;
    }
    
    // Daha güvenilir kontrol: hem class hem display hem de visibility kontrolü
    var computedDisplay = $targetCollapse.css('display');
    var hasShowClass = $targetCollapse.hasClass('show');
    var ariaExpanded = $this.attr('aria-expanded') === 'true';
    var isVisible = $targetCollapse.is(':visible');
    
    var isCurrentlyOpen = hasShowClass || 
                          computedDisplay === 'block' ||
                          ariaExpanded ||
                          (isVisible && computedDisplay !== 'none');
    
    // Toggle: Açıksa kapat, kapalıysa aç
    if (isCurrentlyOpen) {
      // Kapat - zorla kapat
        $targetCollapse.removeClass('show');
      $targetCollapse.css('display', 'none');
        $this.attr('aria-expanded', 'false');
      
      // Bootstrap collapse varsa onu da kullan
      if (typeof $targetCollapse.collapse === 'function') {
        try {
          $targetCollapse.collapse('hide');
        } catch(err) {
          // Zaten manuel kapattık
        }
      }
      } else {
      // Aç - zorla aç
        $targetCollapse.addClass('show');
      $targetCollapse.css('display', 'block');
        $this.attr('aria-expanded', 'true');
      
      // Bootstrap collapse varsa onu da kullan
      if (typeof $targetCollapse.collapse === 'function') {
        try {
          $targetCollapse.collapse('show');
        } catch(err) {
          // Zaten manuel açtık
        }
      }
    }
    
      return false;
  });
  
  // Bootstrap collapse event'lerini dinle ve aria-expanded'ı güncelle
  $(document).on('show.bs.collapse', '.sidebar .collapse', function() {
    var collapseId = $(this).attr('id');
    if (collapseId) {
      $('.sidebar .nav-link[href="#' + collapseId + '"]').attr('aria-expanded', 'true');
    }
  });
  
  $(document).on('hide.bs.collapse', '.sidebar .collapse', function() {
    var collapseId = $(this).attr('id');
    if (collapseId) {
      $('.sidebar .nav-link[href="#' + collapseId + '"]').attr('aria-expanded', 'false');
    }
  });
  
  $(document).on('shown.bs.collapse', '.sidebar .collapse', function() {
    var collapseId = $(this).attr('id');
    if (collapseId) {
      $('.sidebar .nav-link[href="#' + collapseId + '"]').attr('aria-expanded', 'true');
    }
  });
  
  $(document).on('hidden.bs.collapse', '.sidebar .collapse', function() {
    var collapseId = $(this).attr('id');
    if (collapseId) {
      $('.sidebar .nav-link[href="#' + collapseId + '"]').attr('aria-expanded', 'false');
    }
  });

  // Rehberim tablosu: satıra tıklayınca aşağı kart aç/kapa
  $(document).on('click', '.phonebook-row', function() {
    var id = $(this).data('customer-id');
    var $detailsRow = $('.phonebook-details-row[data-customer-id="' + id + '"]');

    if ($detailsRow.length) {
      $detailsRow.toggle();
    }
  });
});

