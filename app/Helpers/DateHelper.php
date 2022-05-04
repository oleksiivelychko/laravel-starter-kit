<?php

namespace App\Helpers;

use Exception;
use IntlDateFormatter;


class DateHelper
{
    public static function since($date): bool|string
    {
        try {
            $fmt = datefmt_create(
                array_key_first(config('settings.intl.' . app()->getLocale())),
                IntlDateFormatter::FULL,
                IntlDateFormatter::FULL,
                array_values(config('settings.intl.' . app()->getLocale()))[0],
                IntlDateFormatter::GREGORIAN,
                'd MMMM, yyyy'
            );
            return datefmt_format($fmt, strtotime($date));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}