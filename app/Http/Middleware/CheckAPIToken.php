<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CheckAPIToken
{
    const EMPTY_TOKEN = 'Empty token';
    const INVALID_TOKEN = 'Invalid token';

    public function handle(Request $request, Closure $next): mixed
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
