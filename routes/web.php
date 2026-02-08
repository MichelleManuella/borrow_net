<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\KategoriController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('auth.login');
    Route::post('/login', 'loginProcess')->name('auth.login.process');
    Route::get('/register', 'register')->name('auth.register');
    Route::post('/register', 'registerProcess')->name('auth.register.process');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->group(function () {

    // dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // kategori alat
    Route::get('/kategori-alat', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::post('/kategori-alat', [KategoriController::class, 'store'])
        ->name('kategori.store');
});
