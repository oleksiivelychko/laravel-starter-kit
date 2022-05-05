<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;


class IsVerifiedEmail extends EnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null): mixed
    {
        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {

            return $request->expectsJson()
                ? abort(403, trans('auth.email-not-verified'))
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice', app()->getLocale()));
        }

        return $next($request);
    }
}
