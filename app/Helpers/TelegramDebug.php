<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Throwable;

/**
 * Debugging tool
 * To get chat_id: after message was sent https://api.telegram.org/bot<bot_token>/getUpdates -> chat -> id
 */
class TelegramDebug
{
    public static function sendException(Throwable $exception): void
    {
        $message =
            "DEBUG LARAVEL APP\n".
            'Method: '.Request::method()."\n".
            'URL: '.Request::url()."\n".
            'File: '.$exception->getFile()."\n".
            'Line:'. $exception->getLine()."\n".
            'Error: '.$exception->getMessage();

        self::send($message);
    }

    public static function sendText(string $message): void
    {
        self::send($message);
    }

    private static function send(string $message): void
    {
        $chat_id = env('TELEGRAM_DEBUG_CHAT_ID');
        $bot_token = env('TELEGRAM_DEBUG_BOT_TOKEN');

        if ($chat_id && $bot_token) {
            Http::post(
                "https://api.telegram.org/bot$bot_token/sendMessage",
                [
                    'chat_id' => $chat_id,
                    'text' => $message,
                ]
            );
        }
    }
}
