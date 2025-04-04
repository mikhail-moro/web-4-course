<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    public function handle(Request $request, Closure $next): Response
    {
        if (User::with(['role'])->findOrFail(Auth::id())["role"]["name"] != "admin") {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
