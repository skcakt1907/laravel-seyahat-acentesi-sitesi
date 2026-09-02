<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Site para birimi katmani.
 *
 * Fiyatlar veritabaninda STERLIN (GBP) tutulur ve tahsilat da GBP yapilir.
 * Ziyaretcinin diline gore ekranda yaklasik karsiligi gosterilir.
 * Kurlar TCMB'den alinir, TL uzerinden capraz hesaplanir.
 */
class SiteCurrency
{
    /** Fiyatlarin tutuldugu ve tahsilatin yapildigi para birimi */
    public const BASE = 'GBP';

    /** Dil -> gosterim para birimi */
    private const LOCALE_MAP = [
        'en' => 'GBP',
        'tr' => 'TRY',
        'de' => 'EUR',
        'nl' => 'EUR',
        'ru' => 'RUB',
        'ar' => 'USD',
    ];

    private const SYMBOLS = [
        'GBP' => '£',
        'EUR' => '€',
        'USD' => '$',
        'RUB' => '₽',
        'TRY' => '₺',
    ];

    /** Kur cekilemezse kullanilacak makul degerler (1 GBP = X) */
    private const FALLBACK = [
        'GBP' => 1.0,
        'EUR' => 1.16,
        'USD' => 1.36,
        'RUB' => 114.0,
        'TRY' => 65.0,
    ];

    /** Aktif dile karsilik gelen para birimi kodu */
    public static function code(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return self::LOCALE_MAP[$locale] ?? self::BASE;
    }

    public static function symbol(?string $code = null): string
    {
        $code = $code ?: self::code();

        return self::SYMBOLS[$code] ?? '';
    }

    /** Gosterilen para birimi tahsilat para biriminden farkli mi? */
    public static function isConverted(?string $code = null): bool
    {
        return ($code ?: self::code()) !== self::BASE;
    }

    /**
     * 1 GBP kac birim eder? (GBP bazli capraz kur tablosu)
     */
    public static function rates(): array
    {
        // Onbellek deposu (DB) erisilemezse fiyat gosterimi sayfayi cokertmesin
        try {
            return self::ratesCached();
        } catch (\Throwable $e) {
            Log::warning('Kur onbellegi okunamadi: '.$e->getMessage());

            return self::FALLBACK;
        }
    }

    private static function ratesCached(): array
    {
        return Cache::remember('site_kurlar_gbp_'.date('Y-m-d-H'), 3600, function () {
            try {
                $res = Http::timeout(10)->get('https://www.tcmb.gov.tr/kurlar/today.xml');

                if (! $res->successful()) {
                    return self::FALLBACK;
                }

                $xml = @simplexml_load_string($res->body());
                if (! $xml) {
                    return self::FALLBACK;
                }

                // TCMB: 1 birim doviz = X TL  (Unit'e bolerek normalize ediyoruz)
                $tl = [];
                foreach ($xml->Currency as $c) {
                    $kod = (string) $c['CurrencyCode'];
                    if (! isset(self::SYMBOLS[$kod])) {
                        continue;
                    }
                    $birim = max(1, (float) $c->Unit);
                    $alis  = (float) str_replace(',', '.', (string) $c->ForexBuying);
                    if ($alis > 0) {
                        $tl[$kod] = $alis / $birim;
                    }
                }

                if (empty($tl['GBP'])) {
                    return self::FALLBACK;
                }

                // Capraz: 1 GBP = (GBP'nin TL degeri) / (hedefin TL degeri)
                // TRY, TCMB listesinde kalem olarak yok (baz para birimi) — dogrudan aliyoruz
                $kurlar = ['GBP' => 1.0, 'TRY' => $tl['GBP']];
                foreach ($tl as $kod => $tlDeger) {
                    if ($kod !== 'GBP' && $tlDeger > 0) {
                        $kurlar[$kod] = $tl['GBP'] / $tlDeger;
                    }
                }

                return $kurlar + self::FALLBACK;
            } catch (\Throwable $e) {
                Log::warning('TCMB kuru alinamadi: '.$e->getMessage());

                return self::FALLBACK;
            }
        });
    }

    /** GBP tutari hedef para birimine cevirir */
    public static function convert(float $gbp, ?string $code = null): float
    {
        $code = $code ?: self::code();

        if ($code === self::BASE) {
            return $gbp;
        }

        $kurlar = self::rates();

        return $gbp * ($kurlar[$code] ?? self::FALLBACK[$code] ?? 1.0);
    }

    /**
     * Ekranda gosterilecek fiyat metni.
     * Cevrilmis para birimlerinde tam sayiya yukari yuvarlanir (kur oynamasina karsi).
     */
    public static function display(float $gbp, ?string $code = null, bool $yaklasik = true): string
    {
        $code = $code ?: self::code();
        $tutar = self::convert($gbp, $code);
        $sembol = self::symbol($code);

        if ($code === self::BASE) {
            return $sembol.number_format($tutar, 2);
        }

        $tutar = ceil($tutar);
        $metin = $sembol.number_format($tutar, 0, ',', self::thousandsSep());

        return $yaklasik ? '≈ '.$metin : $metin;
    }

    /** Binlik ayraci dile gore degisir: Rusca bosluk, DE/NL nokta, digerleri virgul */
    private static function thousandsSep(): string
    {
        return match (app()->getLocale()) {
            'ru'       => ' ',
            'de', 'nl', 'tr' => '.',
            default    => ',',
        };
    }
}
