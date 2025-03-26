<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

// API для получения всех бронирований
Route::get('/reservations', [ReservationController::class, 'index']);

// API для создания бронирования
Route::post('/reservations', [ReservationController::class, 'store']);

// API для удаления бронирования
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
