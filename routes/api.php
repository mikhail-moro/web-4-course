<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AuthTokenMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminReservationController;

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


Route::middleware([AuthTokenMiddleware::class, AdminMiddleware::class])->group(function () {
    Route::post('/admin/users', [AdminUserController::class, 'create']);
    Route::get('/admin/users', [AdminUserController::class, 'read']);
    Route::patch('/admin/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'delete']);

    Route::post('/admin/tables', [AdminTableController::class, 'create']);
    Route::get('/admin/tables', [AdminTableController::class, 'read']);
    Route::patch('/admin/tables/{id}', [AdminTableController::class, 'update']);
    Route::delete('/admin/tables/{id}', [AdminTableController::class, 'delete']);

    Route::post('/admin/reservations', [AdminReservationController::class, 'create']);
    Route::get('/admin/reservations', [AdminReservationController::class, 'read']);
    Route::patch('/admin/reservations/{id}', [AdminReservationController::class, 'update']);
    Route::delete('/admin/reservations/{id}', [AdminReservationController::class, 'delete']);
});
