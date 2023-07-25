<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogVerifiedUserListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        Log::channel('daily')->info(
            'User has verified email: '.$event->user->getEmailForVerification()
        );
    }
}
