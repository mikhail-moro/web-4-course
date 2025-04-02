<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main'); // Главная страница
});

Route::get('/{any}', function () {
    return view('main');
})->where('any', '.*');