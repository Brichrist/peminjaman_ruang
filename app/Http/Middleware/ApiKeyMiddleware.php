<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $validKey = config('app.api_key');

        if (!$validKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key belum dikonfigurasi di server',
            ], 500);
        }

        if ($apiKey !== $validKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key tidak valid',
            ], 401);
        }

        return $next($request);
    }
}
