<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;


class LocaleHelper
{
    public static function translateObject(string $field, string $locale=null): string
    {
        $decoded = json_decode($field);
        $locale = $locale ?: app()->getLocale();
        return $decoded->$locale ?? '';
    }
}
