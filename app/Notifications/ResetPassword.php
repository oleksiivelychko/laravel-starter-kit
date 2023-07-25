<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'locale' => app()->getLocale(),
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage())
            ->subject(__('auth.reset-password'))
            ->view('emails.reset-password', [
                'url' => $url,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ])
        ;
    }
}
