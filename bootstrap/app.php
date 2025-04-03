<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Добавляем API маршруты
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], $e->status);
        });
    })->create();


