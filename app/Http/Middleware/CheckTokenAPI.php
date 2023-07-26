<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class CheckTokenAPI
{
    public const EMPTY_TOKEN = 'Empty token';
    public const INVALID_TOKEN = 'Invalid token';

    public function handle(Request $request, \Closure $next): mixed
    {
        $token = env('API_TOKEN');
        if (!$token) {
            return response()->json(self::EMPTY_TOKEN, 401);
        }

        $apiKey = $request->header('X-API-KEY');
        if ($token !== $apiKey) {
            return response()->json(self::INVALID_TOKEN, 401);
        }

        return $next($request);
    }
}
