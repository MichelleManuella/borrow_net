<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PengembalianController extends Controller
{
    private const DENDA_TERLAMBAT_PER_HARI = 1000;
    private const DENDA_RUSAK = 50000;
    private const DENDA_HILANG = 200000;

    // INDEX
    public function index()
    {
        // peminjaman yang BELUM dikembalikan
        $peminjaman = Peminjaman::with(['user', 'alat'])
            ->where('status', 'disetujui')
            ->get();

        // data pengembalian (riwayat)
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.alat', 'denda'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['menunggu'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%rusak%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%hilang%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%terlambat%']);
            })
            ->whereDoesntHave('denda', function ($query) {
                $query->where('status_bayar', 'lunas');
            })
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

        $statusLower = strtolower($request->status);
        $needsDenda = str_contains($statusLower, 'terlambat')
            || str_contains($statusLower, 'rusak')
            || str_contains($statusLower, 'hilang');

        if ($needsDenda) {
            Denda::create([
                'pengembalian_id' => $pengembalian->id,
                'nominal' => $this->calculateDenda($pengembalian),
                'status_bayar' => 'menunggu',
            ]);
        }

        $peminjaman->alat->increment('stok', (int) $peminjaman->jumlah);


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
        $pengembalian = Pengembalian::with('peminjaman.user', 'peminjaman.alat', 'denda')
            ->findOrFail($id);
        $peminjaman = $pengembalian->peminjaman;
        $defaultDenda = $this->calculateDenda($pengembalian);

        return view('admin.pengembalian.edit', compact('pengembalian', 'peminjaman', 'defaultDenda'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'status'          => 'required|string',
            'keterangan'      => 'nullable|string',
            'nominal_denda'   => 'nullable|integer|min:0',
            'status_bayar'    => 'nullable|in:menunggu,lunas',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => $request->status,
            'keterangan'      => $request->keterangan,
        ]);

        $statusLower = strtolower($request->status);
        $needsDenda = str_contains($statusLower, 'terlambat')
            || str_contains($statusLower, 'rusak')
            || str_contains($statusLower, 'hilang');

        if ($needsDenda) {
            $nominal = $request->filled('nominal_denda')
                ? (int) $request->nominal_denda
                : $this->calculateDenda($pengembalian);
            Denda::updateOrCreate(
                ['pengembalian_id' => $pengembalian->id],
                [
                    'nominal' => $nominal,
                    'status_bayar' => $request->status_bayar ?? 'menunggu',
                ]
            );
        } else {
            $pengembalian->denda()?->delete();
        }

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

    public function confirmDenda(Pengembalian $pengembalian)
    {
        if (!$pengembalian->denda) {
            return redirect()
                ->route('admin.pengembalian.index')
                ->with('error', 'Data denda belum tersedia.');
        }

        $pengembalian->denda->update(['status_bayar' => 'lunas']);

        return redirect()
            ->route('admin.pengembalian.index')
            ->with('success', 'Denda berhasil dikonfirmasi.');
    }

    private function calculateDenda(Pengembalian $pengembalian): int
    {
        $status = strtolower($pengembalian->status ?? '');

        if (str_contains($status, 'rusak')) {
            return self::DENDA_RUSAK;
        }

        if (str_contains($status, 'hilang')) {
            return self::DENDA_HILANG;
        }

        if (str_contains($status, 'terlambat')) {
            $jatuhTempo = Carbon::parse($pengembalian->peminjaman->tanggal_kembali);
            $kembali = Carbon::parse($pengembalian->tanggal_kembali);
            $hariTerlambat = max(0, $jatuhTempo->diffInDays($kembali, false));
            return $hariTerlambat * self::DENDA_TERLAMBAT_PER_HARI;
        }

        return 0;
    }
}
