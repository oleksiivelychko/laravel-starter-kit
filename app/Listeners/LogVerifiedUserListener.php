<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogVerifiedUserListener
{
    public function __construct()
    {
    }

    public function handle($event): void
    {
        Log::channel('daily')->info(
            'User has verified email: '.$event->user->getEmailForVerification()
        );
    }
}
