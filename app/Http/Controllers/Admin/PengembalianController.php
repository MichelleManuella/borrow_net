<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;


class PengembalianController extends Controller
{
    // INDEX
    public function index()
    {
        // peminjaman yang BELUM dikembalikan
        $peminjaman = Peminjaman::with(['user', 'alat'])
            ->where('status', 'disetujui')
            ->get();

        // data pengembalian (riwayat)
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.alat'])
            ->latest()
            ->get();

        return view('admin.pengembalian.pengembalian', compact(
            'peminjaman',
            'pengembalian'
        ));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with(['user', 'alat'])
            ->where('status', 'disetujui')
            ->get();

        return view('admin.pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id'   => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date',
            'status'          => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        // AMBIL DATA PEMINJAMAN DULU
        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // SIMPAN PENGEMBALIAN
        $pengembalian = Pengembalian::create([
            'peminjaman_id'   => $peminjaman->id,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => $request->status,
            'keterangan'      => $request->keterangan,
        ]);


        // TAMBAH LOG
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan pengembalian untuk peminjaman ID ' . $peminjaman->id,
        ]);

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }

    // EDIT
    public function edit($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.user', 'peminjaman.alat')
            ->findOrFail($id);

        return view('admin.pengembalian.edit', compact('pengembalian'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'status'          => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => $request->status,
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()
            ->route('admin.pengembalian.index')
            ->with('success', 'Data pengembalian berhasil diupdate.');
    }

    // DELETE
    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        return redirect()
            ->route('admin.pengembalian.index')
            ->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
