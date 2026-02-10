<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PeminjamanController;

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
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // kategori alat
    Route::get('/kategori-alat', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::post('/kategori-alat', [KategoriController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori-alat/{id}/edit', [KategoriController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori-alat/{id}', [KategoriController::class, 'update'])
        ->name('kategori.update');

    Route::delete('/kategori-alat/{id}', [KategoriController::class, 'destroy'])
        ->name('kategori.destroy');

    // ✅ ALAT (CRUD)
    Route::resource('alat', AlatController::class);

    Route::get('/user', [UserController::class, 'index'])
    ->name('user.index');

    Route::get('/user/create', [UserController::class, 'create'])
    ->name('user.create');

Route::post('/user', [UserController::class, 'store'])
    ->name('user.store');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
    ->name('user.destroy');

    Route::get('/user/{id}/edit', [UserController::class, 'edit'])
    ->name('user.edit');

    Route::put('/user/{id}', [UserController::class, 'update'])
    ->name('user.update');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
    ->name('admin.peminjaman.index');

    // Update status peminjaman
    Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])
    ->name('admin.peminjaman.status');

    // Hapus peminjaman
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])
    ->name('admin.peminjaman.destroy');

    // Form tambah peminjaman
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])
    ->name('admin.peminjaman.create');

    // Simpan data peminjaman baru
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
    ->name('admin.peminjaman.store');

    // Form edit peminjaman
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])
    ->name('admin.peminjaman.edit');

    // Update data peminjaman
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])
    ->name('admin.peminjaman.update');

    // Update status peminjaman (approve/reject)
    Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])
    ->name('admin.peminjaman.status');

    // Form edit peminjaman
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])
    ->name('admin.peminjaman.edit');

    // Update data peminjaman
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])
    ->name('admin.peminjaman.update');

});

