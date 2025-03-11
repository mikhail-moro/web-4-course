<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main'); // Главная страница
});

Route::get('/register', function () {
    return view('register'); // Страница регистрации
});

Route::get('/login', function () {
    return view('login'); // Страница входа
});

