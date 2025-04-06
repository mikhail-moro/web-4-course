<?php

use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




//> Login actions
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
//<

//> Reset passwort 
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
//<

//> HZ
Route::middleware([AuthTokenMiddleware::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user_id' => $request->auth_user_id]);
    });

    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::patch('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    Route::post('/confirm/{id}', [ReservationController::class, 'confirm']);

    Route::get('/tables', TableController::class);
});
//<

//> HZ
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
//<

