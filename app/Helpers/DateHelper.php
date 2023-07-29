<?php

namespace App\Helpers;

class DateHelper
{
    public static function since($date): bool|string
    {
        try {
            return datefmt_format(datefmt_create(
                array_key_first(config('settings.intl.'.app()->getLocale())),
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                array_values(config('settings.intl.'.app()->getLocale()))[0],
                \IntlDateFormatter::GREGORIAN,
                'd MMMM, yyyy'
            ), strtotime($date));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
