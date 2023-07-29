<?php

namespace App\Helpers;

class LocaleHelper
{
    public static function translateObject(string $field, string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return json_decode($field)->{$locale} ?? '';
    }
}
