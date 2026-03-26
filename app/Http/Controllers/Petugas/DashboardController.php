<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        $totalUser = User::count();
        $totalAlat = Alat::count();
        $peminjamanAktif = Peminjaman::whereNull('tanggal_kembali')->count();
        $pengembalianHariIni = Pengembalian::whereDate('tanggal_kembali', Carbon::today())->count();

        return view('petugas.dashboard', compact(
            'totalUser',
            'totalAlat',
            'peminjamanAktif',
            'pengembalianHariIni'
        ));
    }

    public function alatIndex()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        $search = request('q');
        $alatsQuery = Alat::with('kategori');

        if ($search) {
            $alatsQuery->where('nama_alat', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($query) use ($search) {
                    $query->where('nama_kategori', 'like', '%' . $search . '%');
                });
        }

        $alats = $alatsQuery->latest()->get();

        return view('petugas.alat.daftaralat', compact('alats', 'search'));
    }
}
