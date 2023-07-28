<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

class UrlHelper
{
    public static function sortable(string $column): string
    {
        $direction = 'asc';
        if ((Request::exists('sort') && Request::exists('direction'))
            && (Request::query('sort') === $column && 'asc' === Request::query('direction'))) {
            $direction = 'desc';
        }

        $title = trans()->has('dashboard.'.$column) ? trans('dashboard.'.$column) : ucwords($column);
        $url = request()->url().'?sort='.$column.'&direction='.$direction;

        return '<a href="'.$url.'">'.$title.'</a>';
    }
}
