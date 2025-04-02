<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request["auth_user_id"];

        if (!isset($id)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (User::with(['role'])->findOrFail($id)["role"]["name"] != "admin") {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
