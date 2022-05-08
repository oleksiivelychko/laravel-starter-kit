<?php

namespace App\Traits;

use Exception;


trait Translation
{
    /**
     * Pop off the string from JSON by locale as key.
     *
     * @param string $field '{"en":"hello", "uk":"вітаю"}'
     * @param string|null $locale en|uk
     * @param string|bool $parent
     * @return string hello|вітаю
     */
    public function translate(string $field, string $locale=null, bool $parent=false): string
    {
        try {
            if ($parent && isset($this->parent)) {
                $decoded = json_decode($this->parent->$field);
            } else {
                $decoded = json_decode($this->$field);
            }
        } catch (Exception $e) {
            return '';
        }

        $locale = $locale ?: app()->getLocale();
        return $decoded->$locale ?? '';
    }
}
