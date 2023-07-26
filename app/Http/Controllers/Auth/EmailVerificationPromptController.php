<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): Application|RedirectResponse|View|Factory
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('home', app()->getLocale()))
                    : view('auth.verify-email');
    }
}
