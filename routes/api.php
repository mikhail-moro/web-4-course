<?php

use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware([AuthTokenMiddleware::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user_id' => $request->auth_user_id]);
    });

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::patch('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    Route::post('/confirm/{id}', [ReservationController::class, 'confirm']);
});

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
