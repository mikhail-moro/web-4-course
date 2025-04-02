<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Декодируем токен
        $decoded = base64_decode($token);
        if (!$decoded || !str_contains($decoded, '|')) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        [$userId, $expiry] = explode('|', $decoded);

        if (!is_numeric($userId) || !is_numeric($expiry)) {
            return response()->json(['error' => 'Invalid token format'], 401);
        }

        // Проверяем, не истек ли срок токена
        if (now()->timestamp > (int) $expiry) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        // Добавляем user_id в request
        $request->merge(['auth_user_id' => (int) $userId]);

        return $next($request);
    }
}
