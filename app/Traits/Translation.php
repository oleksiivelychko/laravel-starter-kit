<?php

namespace App\Traits;

trait Translation
{
    /**
     * Pop off the string from JSON by locale as key.
     *
     * @param string      $field  '{"en":"hello", "uk":"вітаю"}'
     * @param null|string $locale en|uk
     * @param bool|string $parent
     *
     * @return string hello|вітаю
     */
    public function translate(string $field, string $locale = null, bool $parent = false): string
    {
        try {
            if ($parent && isset($this->parent)) {
                $decoded = json_decode($this->parent->{$field});
            } else {
                $decoded = json_decode($this->{$field});
            }
        } catch (\Exception $e) {
            return '';
        }

        $locale = $locale ?: app()->getLocale();

        return $decoded->{$locale} ?? '';
    }
}
