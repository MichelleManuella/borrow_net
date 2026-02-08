<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('auth.login');
    Route::post('/login', 'loginProcess')->name('auth.login.process');
    Route::get('/register', 'register')->name('auth.register');
    Route::post('/register', 'registerProcess')->name('auth.register.process');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');