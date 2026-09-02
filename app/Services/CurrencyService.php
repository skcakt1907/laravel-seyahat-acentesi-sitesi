<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const TCMB_TODAY_URL = 'https://www.tcmb.gov.tr/kurlar/today.xml';
    private const FRESH_KEY = 'fx_gbp_try_fresh';
    private const BACKUP_KEY = 'fx_gbp_try_backup';
    private const FRESH_TTL_MIN = 60;
    private const BACKUP_TTL_HOUR = 48;

    public static function gbpToTry(): float
    {
        $fresh = Cache::get(self::FRESH_KEY);
        if ($fresh !== null && $fresh > 0) {
            return (float) $fresh;
        }

        $rate = self::fetchFromTcmb();
        if ($rate > 0) {
            Cache::put(self::FRESH_KEY, $rate, now()->addMinutes(self::FRESH_TTL_MIN));
            Cache::put(self::BACKUP_KEY, $rate, now()->addHours(self::BACKUP_TTL_HOUR));
            return $rate;
        }

        $backup = Cache::get(self::BACKUP_KEY);
        if ($backup !== null && $backup > 0) {
            Log::warning('[FX] TCMB unavailable, using backup', ['rate' => $backup]);
            return (float) $backup;
        }

        throw new \RuntimeException('TCMB FX rate unavailable and no cached fallback');
    }

    public static function convertGbpToTry(float $gbp): array
    {
        $rate = self::gbpToTry();
        return [
            'gbp' => $gbp,
            'try' => round($gbp * $rate, 2),
            'rate' => $rate,
        ];
    }

    private static function fetchFromTcmb(): float
    {
        $today = date('dmY');
        $month = date('Ym');
        $datedUrl = "https://www.tcmb.gov.tr/kurlar/{$month}/{$today}.xml";

        $rate = self::fetchRateFromUrl($datedUrl, 'dated');
        if ($rate > 0) {
            return $rate;
        }

        return self::fetchRateFromUrl(self::TCMB_TODAY_URL, 'today');
    }

    private static function fetchRateFromUrl(string $url, string $tag): float
    {
        try {
            $response = Http::timeout(8)->get($url);
            if (!$response->successful()) {
                Log::info('[FX] TCMB miss', ['tag' => $tag, 'status' => $response->status()]);
                return 0;
            }
            $xml = @simplexml_load_string($response->body());
            if (!$xml) {
                Log::warning('[FX] TCMB XML parse fail', ['tag' => $tag]);
                return 0;
            }
            $bulletinDate = (string) ($xml['Tarih'] ?? '');
            foreach ($xml->Currency as $currency) {
                if ((string) $currency['CurrencyCode'] === 'GBP') {
                    $rate = (float) $currency->ForexSelling;
                    if ($rate > 0) {
                        Log::info('[FX] TCMB GBP fresh', ['tag' => $tag, 'rate' => $rate, 'bulletin' => $bulletinDate]);
                        return $rate;
                    }
                }
            }
            Log::warning('[FX] GBP not found', ['tag' => $tag]);
            return 0;
        } catch (\Throwable $e) {
            Log::error('[FX] TCMB error', ['tag' => $tag, 'err' => $e->getMessage()]);
            return 0;
        }
    }
}
