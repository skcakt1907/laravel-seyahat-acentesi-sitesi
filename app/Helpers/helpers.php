<?php

if (!function_exists('upload_as_webp')) {
    /**
     * Upload edilen görseli webp formatına çevirip kaydeder.
     * @return string|null kaydedilen dosya adı
     */
    function upload_as_webp($file, $uploadPath, $prefix = '')
    {
        \Log::info('[upload_as_webp] START', ['prefix' => $prefix, 'uploadPath' => $uploadPath]);

        if (!$file || !$file->isValid()) {
            \Log::error('[upload_as_webp] Invalid file');
            return null;
        }

        \Log::info('[upload_as_webp] File info', [
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original' => $file->getClientOriginalName(),
            'realPath' => $file->getRealPath(),
            'realPathExists' => $file->getRealPath() ? file_exists($file->getRealPath()) : false,
        ]);

        if (!file_exists($uploadPath)) {
            $mkOk = @mkdir($uploadPath, 0777, true);
            \Log::info('[upload_as_webp] mkdir', ['path' => $uploadPath, 'ok' => $mkOk]);
        }

        \Log::info('[upload_as_webp] uploadPath writable?', [
            'path' => $uploadPath,
            'exists' => file_exists($uploadPath),
            'writable' => is_writable($uploadPath),
        ]);

        $filename = $prefix . time() . '_' . uniqid() . '.webp';
        $fullPath = $uploadPath . '/' . $filename;

        $mime = $file->getMimeType();
        $source = null;

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $source = @imagecreatefromjpeg($file->getRealPath());
        } elseif ($mime === 'image/png') {
            $source = @imagecreatefrompng($file->getRealPath());
            if ($source) {
                imagepalettetotruecolor($source);
                imagealphablending($source, false);
                imagesavealpha($source, true);
            }
        } elseif ($mime === 'image/gif') {
            $source = @imagecreatefromgif($file->getRealPath());
        } elseif ($mime === 'image/webp') {
            $source = @imagecreatefromwebp($file->getRealPath());
        } elseif ($mime === 'image/bmp' || $mime === 'image/x-ms-bmp') {
            $source = @imagecreatefrombmp($file->getRealPath());
        }

        \Log::info('[upload_as_webp] source created?', ['source' => $source ? 'yes' : 'NO', 'mime' => $mime]);

        if ($source) {
            // Büyük görselleri max 1920px genişliğe küçült
            $w = imagesx($source);
            $h = imagesy($source);
            $maxW = 1920;
            if ($w > $maxW) {
                $newH = (int) ($h * ($maxW / $w));
                $resized = imagecreatetruecolor($maxW, $newH);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxW, $newH, $w, $h);
                imagedestroy($source);
                $source = $resized;
            }

            // webp desteği varsa webp olarak kaydet
            if (function_exists('imagewebp')) {
                $result = @imagewebp($source, $fullPath, 85);
                $fileExists = file_exists($fullPath);
                $fileSize = $fileExists ? filesize($fullPath) : 0;
                \Log::info('[upload_as_webp] imagewebp result', [
                    'result' => $result,
                    'fullPath' => $fullPath,
                    'fileExists' => $fileExists,
                    'fileSize' => $fileSize,
                ]);
                if ($result && $fileExists && $fileSize > 0) {
                    imagedestroy($source);
                    \Log::info('[upload_as_webp] SUCCESS webp', ['filename' => $filename]);
                    return $filename;
                }
                // Bozuk dosya oluştuysa sil
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // webp başarısız olduysa orijinal formatta kaydet
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $fallbackName = $prefix . time() . '_' . uniqid() . '.' . $ext;
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            // GD ile orijinal formatta kaydet (dosya zaten memory'de)
            $saved = false;
            if (in_array($mime, ['image/jpeg', 'image/jpg'])) {
                $saved = @imagejpeg($source, $fallbackPath, 90);
            } elseif ($mime === 'image/png') {
                $saved = @imagepng($source, $fallbackPath, 8);
            } elseif ($mime === 'image/gif') {
                $saved = @imagegif($source, $fallbackPath);
            }
            imagedestroy($source);

            \Log::info('[upload_as_webp] fallback GD save', ['saved' => $saved, 'path' => $fallbackPath, 'exists' => file_exists($fallbackPath)]);

            if ($saved) {
                return $fallbackName;
            }

            // GD kaydetme de başarısızsa orijinal dosyayı taşı
            $origFallback = $prefix . time() . '_' . uniqid() . '.' . $ext;
            try {
                $file->move($uploadPath, $origFallback);
                \Log::info('[upload_as_webp] MOVE fallback OK', ['file' => $origFallback]);
                return $origFallback;
            } catch (\Throwable $e) {
                \Log::error('[upload_as_webp] MOVE fallback FAILED', ['err' => $e->getMessage()]);
                return null;
            }
        }

        // Fallback: GD desteklemezse orijinal kaydet
        $fallback = $prefix . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        try {
            $file->move($uploadPath, $fallback);
            \Log::info('[upload_as_webp] NO-GD fallback MOVE OK', ['file' => $fallback]);
            return $fallback;
        } catch (\Throwable $e) {
            \Log::error('[upload_as_webp] NO-GD fallback MOVE FAILED', ['err' => $e->getMessage()]);
            return null;
        }
    }
}

if (!function_exists('my_number_format')) {
    function my_number_format($number)
    {
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('get_ip')) {
    function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}

if (!function_exists('generate_code')) {
    function generate_code($length = 8, $uppercase = true, $lowercase = true, $numbers = true, $special = "")
    {
        $seed = '';
        if ($uppercase) $seed .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($lowercase) $seed .= "abcdefghijklmnopqrstuvwxyz";
        if ($numbers) $seed .= "0123456789";
        if ($special) $seed .= $special;
        
        $password = '';
        $seed_length = strlen($seed);
        for ($i = 0; $i < $length; $i++) {
            $password .= $seed[rand(0, $seed_length - 1)];
        }
        return $password;
    }
}

if (!function_exists('tc_no_kontrol')) {
    function tc_no_kontrol($tc = null)
    {
        $filtre = [
            11111111110, 22222222220, 33333333330,
            44444444440, 55555555550, 66666666660,
            77777777770, 88888888880, 99999999990
        ];
        
        $tc = (string) $tc;
        if ($tc[0] == 0 || !ctype_digit($tc) || strlen($tc) != 11 || is_null($tc) || in_array($tc, $filtre)) {
            return false;
        }
        
        $tek_toplam = $tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8];
        $cift_toplam = $tc[1] + $tc[3] + $tc[5] + $tc[7];
        $on_sonuc = ($tek_toplam * 7) - $cift_toplam;
        $on_toplam = 0;
        
        for ($i = 0; $i < 10; $i++) {
            $on_toplam += $tc[$i];
        }
        
        if ($on_sonuc % 10 != $tc[9] || $on_toplam % 10 != $tc[10]) {
            return false;
        }
        
        return true;
    }
}

if (!function_exists('tl_format')) {
    function tl_format($deger)
    {
        return number_format($deger, 2, ',', '.');
    }
}

if (!function_exists('seo_url')) {
    function seo_url($str, $options = [])
    {
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        
        $defaults = [
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'transliterate' => true,
        ];
        
        $options = array_merge($defaults, $options);
        $dmr = $defaults["delimiter"];
        
        $char_map = [
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            ' ' => $dmr
        ];
        
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        $str = trim($str, $options['delimiter']);
        
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
}

if (!function_exists('kisa_metin')) {
    function kisa_metin($metin, $uzunluk = 50)
    {
        if (strlen($metin) > $uzunluk) {
            $metin = mb_substr($metin, 0, $uzunluk) . "...";
            $metin_son = strrchr($metin, " ");
            $metin = str_replace($metin_son, " ...", $metin);
        }
        return strip_tags($metin);
    }
}

if (!function_exists('kdv_ekle')) {
    function kdv_ekle($tutar, $oran)
    {
        $kdv = $tutar * ($oran / 100);
        return $tutar + $kdv;
    }
}

if (!function_exists('kdv_cikar')) {
    function kdv_cikar($tutar, $oran)
    {
        return $tutar / (1 + ($oran / 100));
    }
}

if (!function_exists('tarih_format')) {
    function tarih_format($tarih)
    {
        if (!$tarih) {
            return date('d.m.Y');
        }
        
        try {
            return date('d.m.Y H:i', strtotime($tarih));
        } catch (\Exception $e) {
            return date('d.m.Y');
        }
    }
}

if (!function_exists('format_price')) {
    function format_price($amount, $currency = null)
    {
        if ($currency === null) {
            $currency = \App\Helpers\CurrencyHelper::getCurrency();
        }
        
        return \App\Helpers\CurrencyHelper::format($amount, $currency);
    }
}

if (!function_exists('convert_price')) {
    function convert_price($amount, $currency = null)
    {
        if ($currency === null) {
            $currency = \App\Helpers\CurrencyHelper::getCurrency();
        }
        
        return \App\Helpers\CurrencyHelper::convert($amount, $currency);
    }
}

if (!function_exists('get_package_image_path')) {
    /**
     * Paket resim yolunu optimize şekilde döndürür
     * Cache kullanarak file_exists kontrollerini minimize eder
     */
    function get_package_image_path($resim_adi)
    {
        if (empty($resim_adi)) {
            return asset('tema/img/noimage.png');
        }
        
        // Eğer DB'de path tutuluyorsa (ör. "tema/uploads/paketler/xxx.jpg") direkt onu kullan.
        // Not: Bazı eski kayıtlarda "kapak/xxx.jpg" gibi relatif yollar olabilir; bunlarda fallback'e düşer.
        $raw = (string) $resim_adi;
        if (str_contains($raw, '/') || str_contains($raw, '\\')) {
            $clean = ltrim(str_replace('\\', '/', $raw), '/');
            $direct = public_path($clean);
            if (file_exists($direct)) {
                return asset($clean);
            }
            // fallback araması için sadece dosya adı ile devam et
            $resim_adi = basename($clean);
        }

        static $cache = [];
        $cache_key = md5((string) $resim_adi);
        
        if (isset($cache[$cache_key])) {
            return $cache[$cache_key];
        }
        
        $klasorler = ['kapak/', '', 'kucuk/'];
        $basePublicPath = public_path('tema/uploads/webpaketleri/');
        $baseThemePath = base_path('tema/uploads/webpaketleri/');
        // Opsiyonel public alt dizin ön eki (örn: shared hosting'te /public altında yayın)
        $publicPrefix = trim(env('ASSET_PUBLIC_PREFIX', ''), '/');
        
        foreach ($klasorler as $klasor) {
            $dosya_yolu = $baseThemePath . $klasor . $resim_adi;
            if (!file_exists($dosya_yolu)) {
                // Public altı fallback
                $dosya_yolu = $basePublicPath . $klasor . $resim_adi;
            }
            if (file_exists($dosya_yolu)) {
                $urlPath = 'tema/uploads/webpaketleri/' . $klasor . $resim_adi;
                $resim_yolu = asset($publicPrefix ? ($publicPrefix . '/' . $urlPath) : $urlPath);
                $cache[$cache_key] = $resim_yolu;
                return $resim_yolu;
            }
        }
        
        $resim_yolu = asset('tema/img/noimage.png');
        $cache[$cache_key] = $resim_yolu;
        return $resim_yolu;
    }
}

if (!function_exists('localized_route')) {
    /**
     * Route'a mevcut dil parametresini otomatik ekle
     */
    function localized_route($name, $parameters = [], $absolute = true)
    {
        $locale = app()->getLocale();
        $defaultLocale = 'tr';
        
        // Eğer dil Türkçe değilse lang parametresini ekle
        if ($locale !== $defaultLocale) {
            if (is_array($parameters)) {
                // Array ise direkt ekle
                $parameters['lang'] = $locale;
            } elseif (empty($parameters)) {
                // Boş ise sadece lang ekle
                $parameters = ['lang' => $locale];
            } else {
                // Tek değer ise (örneğin ID), array'e çevir ve lang ekle
                $params = is_array($parameters) ? $parameters : [$parameters];
                $params['lang'] = $locale;
                $parameters = $params;
            }
        }
        
        $url = route($name, $parameters, $absolute);
        
        // Mevcut request'in port bilgisini mutlaka koru
        if ($absolute) {
            $parsed = parse_url($url);
            $currentPort = request()->getPort();
            
            // Eğer mevcut port 80/443 değilse ve URL'de port yoksa veya farklıysa düzelt
            if ($currentPort && $currentPort !== 80 && $currentPort !== 443) {
                $scheme = $parsed['scheme'] ?? 'http';
                $host = $parsed['host'] ?? request()->getHost();
                $path = $parsed['path'] ?? '/';
                $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
                
                // URL'de port yoksa veya farklıysa, mevcut port'u ekle
                if (!isset($parsed['port']) || $parsed['port'] != $currentPort) {
                    $url = "{$scheme}://{$host}:{$currentPort}{$path}{$query}{$fragment}";
                }
            }
        }
        
        return $url;
    }
}

if (!function_exists('localized_url')) {
    /**
     * URL'e mevcut dil parametresini otomatik ekle
     */
    function localized_url($path = null, $parameters = [], $secure = null)
    {
        $locale = app()->getLocale();
        $defaultLocale = 'tr';
        
        // Eğer dil Türkçe değilse lang parametresini ekle
        if ($locale !== $defaultLocale) {
            if (is_array($parameters)) {
                $parameters['lang'] = $locale;
            } else {
                $parameters = array_merge(['lang' => $locale], (array) $parameters);
            }
        }
        
        $url = url($path, $parameters, $secure);
        
        // Eğer path null ise ve zaten query string varsa, lang parametresini ekle
        if ($path === null && $locale !== $defaultLocale) {
            $parsed = parse_url($url);
            $query = [];
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $query);
            }
            if (!isset($query['lang'])) {
                $query['lang'] = $locale;
                $newQuery = http_build_query($query);
                $url = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? '') . ($parsed['path'] ?? '/') . '?' . $newQuery;
            }
        }
        
        // Mevcut request'in port bilgisini mutlaka koru
        $parsed = parse_url($url);
        $currentPort = request()->getPort();
        
        // Eğer mevcut port 80/443 değilse ve URL'de port yoksa veya farklıysa düzelt
        if ($currentPort && $currentPort !== 80 && $currentPort !== 443) {
            $scheme = $parsed['scheme'] ?? 'http';
            $host = $parsed['host'] ?? request()->getHost();
            $path = $parsed['path'] ?? '/';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
            
            // URL'de port yoksa veya farklıysa, mevcut port'u ekle
            if (!isset($parsed['port']) || $parsed['port'] != $currentPort) {
                $url = "{$scheme}://{$host}:{$currentPort}{$path}{$query}{$fragment}";
            }
        }
        
        return $url;
    }
}

if (!function_exists('can_access_page')) {
    /**
     * Admin'in belirli bir sayfaya erişim yetkisi olup olmadığını kontrol eder
     * 
     * @param string $routeName Route name (örn: 'admin.uyeler.index')
     * @return bool
     */
    function can_access_page($routeName)
    {
        // Admin girişi yapılmamışsa false döndür
        if (!session()->has('admin_logged_in') || !session('admin_logged_in')) {
            return false;
        }
        
        $adminRol = session('admin_rol', 2);
        $adminId = session('admin_id');
        
        // Patron (rol=1) her şeye erişebilir
        if ($adminRol == 1) {
            return true;
        }
        
        // Müşteri (rol=4) için false (müşteri paneli ayrı)
        if ($adminRol == 4) {
            return false;
        }
        
        // Çalışan (rol=2) ve Bayi (rol=3) için yetki kontrolü
        if (in_array($adminRol, [2, 3])) {
            if (!\Illuminate\Support\Facades\Schema::hasTable('yonetici_yetkileri')) {
                // Tablo yoksa varsayılan olarak false (güvenlik)
                return false;
            }
            
            $yetkiKaydi = \Illuminate\Support\Facades\DB::table('yonetici_yetkileri')
                ->where('yonetici_id', $adminId)
                ->where('sayfa_route', $routeName)
                ->first();
            
            // Yetki kaydı varsa, gorebilir değerine bak
            if ($yetkiKaydi) {
                return $yetkiKaydi->gorebilir == 1;
            }
            
            // Yetki kaydı yoksa, sadece dashboard'a izin ver
            return $routeName === 'admin.dashboard';
        }
        
        return false;
    }
}

/* ---------------- Site fiyat gosterimi (GBP bazli) ---------------- */

if (! function_exists('fiyat')) {
    /**
     * Veritabanindaki GBP fiyati, ziyaretcinin diline gore ekranda gosterir.
     * Tahsilat her zaman GBP yapilir; cevrilmis para birimlerinde "≈" isareti konur.
     */
    function fiyat($gbp, bool $yaklasik = true): string
    {
        return \App\Helpers\SiteCurrency::display((float) $gbp, null, $yaklasik);
    }
}

if (! function_exists('fiyat_gbp')) {
    /** Tahsilat tutari — her zaman sterlin. */
    function fiyat_gbp($gbp): string
    {
        return '£'.number_format((float) $gbp, 2);
    }
}

if (! function_exists('para_cevrildi_mi')) {
    /** Gosterilen para birimi sterlinden farkli mi? (uyari notu icin) */
    function para_cevrildi_mi(): bool
    {
        return \App\Helpers\SiteCurrency::isConverted();
    }
}

/* ---------------- Icerik cevirisi (DB tablolarindaki metinler) ---------------- */

if (! function_exists('ic')) {
    /**
     * Veritabanindan gelen bir satirin alanini aktif dilde dondurur.
     * Ana kolon = kaynak dil (Ingilizce). Ceviri `ceviri` JSON kolonunda:
     *   {"de": {"title": "...", "description": "..."}, "ru": {...}}
     * Ceviri yoksa ana kolon kullanilir; boylece eksik ceviri sayfayi bosaltmaz.
     *
     * @param  object|array|null  $satir
     */
    function ic($satir, string $alan, ?string $dil = null)
    {
        if (! $satir) {
            return '';
        }

        $dizi = is_array($satir) ? $satir : (array) $satir;
        $anaDeger = $dizi[$alan] ?? '';

        $dil = $dil ?: app()->getLocale();
        if ($dil === 'en') {                 // kaynak dil
            return $anaDeger;
        }

        $ham = $dizi['ceviri'] ?? null;
        if (blank($ham)) {
            return $anaDeger;
        }

        $ceviri = is_string($ham) ? json_decode($ham, true) : (array) $ham;
        if (! is_array($ceviri)) {
            return $anaDeger;
        }

        $deger = $ceviri[$dil][$alan] ?? null;

        return (is_string($deger) && trim($deger) !== '') ? $deger : $anaDeger;
    }
}

if (! function_exists('ic_diller')) {
    /** Icerik cevirisi yapilan diller (Ingilizce kaynak oldugu icin listede yok). */
    function ic_diller(): array
    {
        return [
            'tr' => 'Türkçe',
            'de' => 'Almanca',
            'nl' => 'Hollandaca',
            'ru' => 'Rusça',
            'ar' => 'Arapça',
        ];
    }
}

if (! function_exists('ceviri_derle')) {
    /**
     * Admin formundan gelen ceviri dizisini JSON'a cevirir.
     * Bos alanlar atilir; hicbir dil doldurulmadiysa null doner (kolon bos kalir).
     */
    function ceviri_derle($girdi): ?string
    {
        if (! is_array($girdi)) {
            return null;
        }

        $temiz = [];
        foreach ($girdi as $dil => $alanlar) {
            if (! is_array($alanlar)) {
                continue;
            }
            foreach ($alanlar as $alan => $deger) {
                $deger = is_string($deger) ? trim($deger) : '';
                if ($deger !== '') {
                    $temiz[$dil][$alan] = $deger;
                }
            }
        }

        return $temiz ? json_encode($temiz, JSON_UNESCAPED_UNICODE) : null;
    }
}
