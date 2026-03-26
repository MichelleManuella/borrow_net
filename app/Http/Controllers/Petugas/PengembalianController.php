<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    private const DENDA_TERLAMBAT_PER_HARI = 1000;
    private const DENDA_RUSAK = 50000;
    private const DENDA_HILANG = 200000;

    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

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

        return view('petugas.pengembalian.index', compact('pengembalian'));
    }

    public function edit(Pengembalian $pengembalian)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        $peminjaman = $pengembalian->peminjaman;
        $defaultDenda = $this->calculateDenda($pengembalian);

        return view('petugas.pengembalian.edit', compact('pengembalian', 'peminjaman', 'defaultDenda'));
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|string',
            'nominal_denda' => 'nullable|integer|min:0',
            'status_bayar' => 'nullable|in:menunggu,lunas',
        ]);

        $status = strtolower($request->status);
        $pengembalian->status = $request->status;
        $pengembalian->save();

        $needsDenda = str_contains($status, 'terlambat')
            || str_contains($status, 'rusak')
            || str_contains($status, 'hilang');

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
            ->route('petugas.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function confirmDenda(Pengembalian $pengembalian)
    {
        $user = Auth::user();
        if (!$user || $user->akun_role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        if (!$pengembalian->denda) {
            return redirect()
                ->route('petugas.pengembalian.index')
                ->with('error', 'Data denda belum tersedia.');
        }

        $pengembalian->denda->update(['status_bayar' => 'lunas']);

        return redirect()
            ->route('petugas.pengembalian.index')
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
