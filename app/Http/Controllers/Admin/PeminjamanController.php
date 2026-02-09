<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
{
    $peminjaman = Peminjaman::with(['user', 'alat'])->latest()->get();
    
    // Sesuaikan dengan nama file view Anda
    return view('admin.peminjaman.peminjaman', compact('peminjaman'));
    // Jika file Anda: peminjaman.blade.php
}
}
