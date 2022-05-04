<?php

namespace App\Helpers;

use Exception;


class BladeHelper
{
    public static function isLocaleErrors($errors=null, ?string $locale=null): array
    {
        try {
            $locales = array_values(config('settings.languages'));
            $errorsBag = array_keys($errors?->getMessages()) ?: [];
            return array_filter($errorsBag, function ($item) use ($locale, $locales) {
                $explode = explode('__', $item);
                if (isset($explode[1]) && $locale === $explode[1] && in_array($explode[1], $locales)) {
                    return true;
                } else {
                    return false;
                }
            });
        } catch (Exception $e) {
            return [];
        }
    }
}
