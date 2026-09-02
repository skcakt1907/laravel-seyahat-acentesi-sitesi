<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = ['tr', 'en', 'de', 'nl', 'ru', 'ar'];
        $defaultLocale = 'en';
        $localeMap = [
            'tr' => 1,
            'en' => 2,
            'ar' => 3,
            'de' => 4,
            'nl' => 5,
            'ru' => 6,
        ];

        $applyLocale = function (string $locale) use ($localeMap): void {
            app()->setLocale($locale);
            session([
                'locale' => $locale,
                'k_dil' => $localeMap[$locale] ?? 1,
            ]);
        };

        $resolved = null;
        $cookieToSet = null;

        // ?lang= URL parametresi tamamen yok sayılıyor — dil sadece cookie/session/header üzerinden belirlenir

        // 2. Session
        if (!$resolved) {
            $sessionLocale = session('locale');
            if ($sessionLocale && in_array($sessionLocale, $availableLocales, true)) {
                $resolved = $sessionLocale;
            }
        }

        // 3. Cookie
        if (!$resolved) {
            $cookieLocale = $request->cookie('locale_preference');
            if ($cookieLocale && in_array($cookieLocale, $availableLocales, true)) {
                $resolved = $cookieLocale;
            }
        }

        // 4. Tarayıcı Accept-Language
        if (!$resolved) {
            $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE', '');
            if ($acceptLanguage) {
                foreach (explode(',', $acceptLanguage) as $part) {
                    $langPart = trim(explode(';', $part)[0]);
                    $langCode = strtolower(substr($langPart, 0, 2));
                    if (in_array($langCode, $availableLocales, true)) {
                        $resolved = $langCode;
                        break;
                    }
                }
            }
        }

        // 5. Varsayılan
        if (!$resolved) {
            $resolved = $defaultLocale;
        }

        $applyLocale($resolved);

        $response = $next($request);

        if ($cookieToSet) {
            $response->withCookie(cookie('locale_detected', '1', 60 * 24 * 365));
            $response->withCookie(cookie('locale_preference', $cookieToSet, 60 * 24 * 365));
        }

        return $response;
    }
}
