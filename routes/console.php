<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('test-mail', function () {
    Mail::send('components/application-version', [], function ($message) {
        $message->to('test@test.test')->subject('test mail');
    });
    $this->comment('check mailbox at https://mail.laravel-starter-kit.local');
})->purpose('Test sending an email to MailHog server.');
