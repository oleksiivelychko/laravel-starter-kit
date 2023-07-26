<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class LocaleController extends Controller
{
    public function change(string $locale): Redirector|Application|RedirectResponse
    {
        if (!$locale || !in_array($locale, array_values(config('settings.languages')))) {
            $locale = config('app.fallback_locale');
        }

        app()->setLocale($locale);

        $prevRoute = app('router')->getRoutes()->match(app('request')->create(url()->previous()));
        if ($prevRoute) {
            $prevRoute->setParameter('locale', $locale);

            return redirect(route($prevRoute->getName(), $prevRoute->parameters()));
        }

        return redirect(route('home', $locale));
    }
}
