<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Controllers\Middleware;

class ResourcesCorsMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        $allowedOrigins = [
            'http://194.87.238.106:5173',
            'http://194.87.238.106:8000',
            'http://localhost:5173',
            'http://localhost:8000',
        ];

        $origin = $request->headers->get('Origin');

        if (in_array($origin, $allowedOrigins)) {
            $headers = [
                'Access-Control-Allow-Origin'      => $origin,
                'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age'           => '86400',
                'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN'
            ];

            if ($request->isMethod('OPTIONS')) {
                return response()->json('{"method":"OPTIONS"}', 200, $headers);
            }

            $response = $next($request);
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }

            return $response;
        }

        return $next($request);
    }
}
