<?php

namespace App\Helpers;


class LocaleHelper
{
    public static function translateObject(string $field, string $locale=null): string
    {
        $decoded = json_decode($field);
        $locale = $locale ?: app()->getLocale();
        return $decoded->$locale ?? '';
    }
}
