<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword;

class PasswordConfirm extends RequirePassword
{
    public function handle($request, \Closure $next, $redirectToRoute = null, $passwordTimeoutSeconds = null)
    {
        if ($this->shouldConfirmPassword($request, $passwordTimeoutSeconds)) {
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => trans('auth.password-required'),
                ], 423);
            }

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?: 'password.confirm', app()->getLocale())
            );
        }

        return $next($request);
    }
}
