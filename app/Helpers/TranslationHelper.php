<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Lang;

class TranslationHelper
{
    /**
     * TR mesaj değerlerinden (lang/tr/messages.php) key bulup,
     * aktif dile göre (lang/<locale>/messages.php) çevirisini döndürür.
     * Eşleşme bulunamazsa metni aynen döndürür.
     */
    public static function translate($text, $targetLanguage = null)
    {
        if (!is_string($text) || $text === '') {
            return $text;
        }

        $locale = is_string($targetLanguage) && $targetLanguage !== ''
            ? $targetLanguage
            : (session('locale') ?: app()->getLocale());

        if (!$locale || $locale === 'tr') {
            return $text;
        }

        static $reverseMap = null;
        if ($reverseMap === null) {
            $reverseMap = [];
            $trMessages = Lang::get('messages', [], 'tr');
            if (is_array($trMessages)) {
                foreach ($trMessages as $key => $value) {
                    if (is_string($value) && $value !== '') {
                        $reverseMap[$value] = $key;
                    }
                }
            }
        }

        $key = $reverseMap[$text] ?? null;
        if (!$key) {
            return $text;
        }

        $translated = Lang::get("messages.$key", [], $locale);
        return is_string($translated) && $translated !== '' ? $translated : $text;
    }

    /**
     * HTML içeriği için de çeviri yapılmaz.
     */
    private static function translateHtml(string $html, string $targetLanguage): string
    {
        return $html;
    }
}


