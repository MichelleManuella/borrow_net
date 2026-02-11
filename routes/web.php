<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LogAktivitasController;

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
Route::middleware(['auth'])->name('admin.')->prefix('admin')->group(function () {

    // ================= DASHBOARD =================
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ================= KATEGORI =================
    Route::get('/kategori-alat', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori-alat', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori-alat/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori-alat/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori-alat/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // ================= ALAT =================
    Route::resource('alat', AlatController::class);

    // ================= USER =================
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // ================= PEMINJAMAN =================
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.status');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    // ================= PENGEMBALIAN ================

        // halaman pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->name('pengembalian.index');

    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])
        ->name('pengembalian.create');

    Route::post('/pengembalian', [PengembalianController::class, 'store'])
        ->name('pengembalian.store');

    Route::get('/pengembalian/{id}/edit', [PengembalianController::class, 'edit'])
        ->name('pengembalian.edit');

    Route::put('/pengembalian/{id}', [PengembalianController::class, 'update'])
        ->name('pengembalian.update');

    Route::delete('/pengembalian/{id}', [PengembalianController::class, 'destroy'])
        ->name('pengembalian.destroy');

});

    Route::prefix('admin')->group(function () {
        Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])
            ->name('admin.log.log');
    });    
