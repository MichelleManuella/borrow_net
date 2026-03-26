<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $totalUser = User::count();
        $totalAlat = Alat::count();
        $peminjamanAktif = Peminjaman::whereNull('tanggal_kembali')->count();
        $pengembalianHariIni = Pengembalian::whereDate('tanggal_kembali', Carbon::today())->count();

        return view('peminjam.dashboard', compact(
            'totalUser',
            'totalAlat',
            'peminjamanAktif',
            'pengembalianHariIni'
        ));
    }

    public function alatIndex()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $search = request('q');
        $alatsQuery = Alat::with('kategori')
            ->where('kondisi', 'Baik')
            ->where('stok', '>', 0);

        if ($search) {
            $alatsQuery->where('nama_alat', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($query) use ($search) {
                    $query->where('nama_kategori', 'like', '%' . $search . '%');
                });
        }

        $alats = $alatsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.daftar_alat.daftaralat', compact('alats', 'search'));
    }

    public function peminjamanIndex()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $peminjaman = Peminjaman::with(['alat', 'pengembalian'])
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('status', 'menunggu')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'disetujui')
                            ->whereDoesntHave('pengembalian');
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.peminjaman.formpeminjaman', compact('peminjaman'));
    }

    public function peminjamanCreate(Alat $alat)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $alatTidakTersedia = $alat->kondisi !== 'Baik'
            || $alat->stok < 1;

        if ($alatTidakTersedia) {
            return redirect()
                ->route('peminjam.alat.index')
                ->with('error', 'Alat tidak tersedia untuk dipinjam.');
        }

        return view('peminjam.peminjaman.create', compact('alat'));
    }

    public function peminjamanStore(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'nullable|string',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        $alatTidakTersedia = $alat->kondisi !== 'Baik'
            || $alat->stok < (int) $request->jumlah;

        if ($alatTidakTersedia) {
            return redirect()
                ->route('peminjam.alat.index')
                ->with('error', 'Alat tidak tersedia untuk dipinjam.');
        }

        Peminjaman::create([
            'user_id' => $user->id,
            'alat_id' => $alat->id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah' => $request->jumlah,
            'keperluan' => $request->keperluan,
            'status' => 'menunggu',
        ]);

        $alat->decrement('stok', (int) $request->jumlah);

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function peminjamanCancel(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        if ($peminjaman->user_id !== $user->id || strtolower($peminjaman->status) !== 'menunggu') {
            return redirect()
                ->route('peminjam.peminjaman.index')
                ->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        $peminjaman->alat->increment('stok', (int) $peminjaman->jumlah);
        $peminjaman->delete();

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function pengembalianIndex()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $selectedPeminjamanId = request('peminjaman_id');
        $peminjamanBaseQuery = Peminjaman::with('alat')
            ->where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereDoesntHave('pengembalian');

        $peminjamanOptions = (clone $peminjamanBaseQuery)->get();

        $peminjaman = $peminjamanBaseQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.pengembalian.formpengembalian', compact(
            'peminjaman',
            'peminjamanOptions',
            'selectedPeminjamanId'
        ));
    }

    public function pengembalianStore(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('alat')
            ->where('id', $request->peminjaman_id)
            ->where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereDoesntHave('pengembalian')
            ->first();

        if (!$peminjaman) {
            return redirect()
                ->route('peminjam.pengembalian.index')
                ->with('error', 'Peminjaman tidak valid untuk pengembalian.');
        }

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'Menunggu',
            'keterangan' => $request->keterangan,
        ]);

        $peminjaman->alat->increment('stok', (int) $peminjaman->jumlah);

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Pengembalian berhasil diajukan.');
    }

    public function riwayatIndex()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'peminjam') {
            abort(403, 'Unauthorized');
        }

        $riwayat = Peminjaman::with(['alat'])
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('status', 'ditolak')
                    ->orWhereHas('pengembalian');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.riwayat_peminjaman.riwayatpeminjaman', compact('riwayat'));
    }
}
