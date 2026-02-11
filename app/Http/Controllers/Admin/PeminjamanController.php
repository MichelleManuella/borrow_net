<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // 1. List data peminjaman
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'alat'])->latest()->get();
        return view('admin.peminjaman.peminjaman', compact('peminjaman'));
    }

    // 2. Form tambah peminjaman
    public function create()
    {
        $users = \App\Models\User::all();
        $alats = \App\Models\Alat::all();
        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    // 3. Simpan peminjaman baru
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required',
        'alat_id' => 'required',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
    ]);

    // SIMPAN PEMINJAMAN
    $peminjaman = Peminjaman::create([
        'user_id' => $request->user_id,
        'alat_id' => $request->alat_id,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_kembali' => $request->tanggal_kembali,
        'status' => 'menunggu',
    ]);

    // TAMBAH LOG
    LogAktivitas::create([
        'user_id' => Auth::id(),
        'aktivitas' => 'Menambahkan peminjaman ID ' . $peminjaman->id,
    ]);

    return redirect()->route('admin.peminjaman.index')
        ->with('success', 'Peminjaman berhasil ditambahkan.');
}


    // 4. Form edit peminjaman
    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $users = \App\Models\User::all();
        $alats = \App\Models\Alat::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'alats'));
    }

    // 5. Update peminjaman
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'alat_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update($request->all());

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // 6. Hapus peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    // 7. Update status approve/reject
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Pastikan value status valid
        $validStatus = ['menunggu', 'disetujui', 'ditolak'];
        $status = strtolower($request->status);

        if (!in_array($status, $validStatus)) {
            return redirect()->route('admin.peminjaman.index')
                ->with('error', 'Status tidak valid.');
        }

        $peminjaman->status = $status;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}
