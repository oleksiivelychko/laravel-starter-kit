<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->segment(1);
        if (in_array($locale, array_values(config('settings.languages')))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
