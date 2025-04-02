<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Kernel;
use App\Http\Middleware\AuthTokenMiddleware;

// API для получения всех бронирований
Route::get('/reservations', [ReservationController::class, 'index']);

// API для создания бронирования
Route::post('/reservations', [ReservationController::class, 'store']);

// API для удаления бронирования
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/user', function (Request $request) {
    return response()->json(['user_id' => $request->auth_user_id]);
})->middleware(AuthTokenMiddleware::class);

/*
Route::middleware('auth.token')->get('/user', function (Request $request) {
    return response()->json(['user_id' => $request->auth_user_id]);
});
*/