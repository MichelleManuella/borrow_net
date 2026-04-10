<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // 1. List data peminjaman
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'alat'])
            ->whereDoesntHave('pengembalian')
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.peminjaman.peminjaman', compact('peminjaman'));
    }

    // 2. Form tambah peminjaman
    public function create()
    {
        if (optional(Auth::user())->akun_role === 'petugas') {
            abort(403, 'Unauthorized');
        }

        $users = \App\Models\User::all();
        $alats = \App\Models\Alat::all();
        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    // 3. Simpan peminjaman baru
    public function store(Request $request)
    {
        if (optional(Auth::user())->akun_role === 'petugas') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required',
            'alat_id' => 'required',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'nullable|string',
        ]);

        $alat = \App\Models\Alat::findOrFail($request->alat_id);
        $jumlah = (int) $request->jumlah;

        if ($alat->stok < $jumlah) {
            return redirect()->route('admin.peminjaman.create')
                ->withErrors(['jumlah' => 'Stok tidak mencukupi untuk peminjaman ini.']);
        }

        // SIMPAN PEMINJAMAN
        $peminjaman = Peminjaman::create([
            'user_id' => $request->user_id,
            'alat_id' => $request->alat_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah' => $jumlah,
            'keperluan' => $request->keperluan,
            'status' => 'menunggu',
        ]);

        $alat->decrement('stok', $jumlah);

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
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        $oldAlatId = $peminjaman->alat_id;
        $oldJumlah = (int) $peminjaman->jumlah;
        $newAlatId = (int) $request->alat_id;
        $newJumlah = (int) $request->jumlah;

        if ($oldAlatId !== $newAlatId) {
            $peminjaman->alat->increment('stok', $oldJumlah);

            $newAlat = \App\Models\Alat::findOrFail($newAlatId);
            if ($newAlat->stok < $newJumlah) {
                return redirect()->route('admin.peminjaman.edit', $peminjaman->id)
                    ->withErrors(['jumlah' => 'Stok alat baru tidak mencukupi.']);
            }
            $newAlat->decrement('stok', $newJumlah);
        } else {
            $diff = $newJumlah - $oldJumlah;
            if ($diff > 0) {
                if ($peminjaman->alat->stok < $diff) {
                    return redirect()->route('admin.peminjaman.edit', $peminjaman->id)
                        ->withErrors(['jumlah' => 'Stok tidak mencukupi untuk penambahan jumlah.']);
                }
                $peminjaman->alat->decrement('stok', $diff);
            } elseif ($diff < 0) {
                $peminjaman->alat->increment('stok', abs($diff));
            }
        }

        $peminjaman->update($request->only([
            'user_id',
            'alat_id',
            'tanggal_pinjam',
            'tanggal_kembali',
            'jumlah',
            'keperluan',
        ]));

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
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        // Pastikan value status valid
        $validStatus = ['menunggu', 'disetujui', 'ditolak'];
        $status = strtolower($request->status);

        if (!in_array($status, $validStatus)) {
            return redirect()->route('admin.peminjaman.index')
                ->with('error', 'Status tidak valid.');
        }

        $previousStatus = $peminjaman->status;

        if ($status === 'disetujui' && $previousStatus !== 'disetujui') {
            if ($previousStatus === 'ditolak') {
                $jumlah = (int) $peminjaman->jumlah;
                if ($peminjaman->alat->stok < $jumlah) {
                    return redirect()->route('admin.peminjaman.index')
                        ->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman.');
                }
                $peminjaman->alat->decrement('stok', $jumlah);
            }
        }

        if ($status === 'ditolak' && $previousStatus !== 'ditolak') {
            $peminjaman->alat->increment('stok', (int) $peminjaman->jumlah);
        }

        $peminjaman->status = $status;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    // 8. Riwayat peminjaman (sudah dikembalikan)
    public function riwayat()
    {
        $this->authorizeRiwayatAccess();

        $search = request('q');
        $statusFilter = request('status');

        $riwayatQuery = Peminjaman::with(['user', 'alat', 'pengembalian.denda'])
            ->whereHas('pengembalian');

        $this->applyRiwayatFilters($riwayatQuery, $search, $statusFilter);

        $riwayat = $riwayatQuery
            ->leftJoin('pengembalian', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->orderByDesc('pengembalian.tanggal_kembali')
            ->orderByDesc('peminjaman.tanggal_kembali')
            ->select('peminjaman.*')
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjaman.riwayat', compact('riwayat'));
    }

    public function exportRiwayat(Request $request)
    {
        $this->authorizeRiwayatAccess();

        $search = $request->query('q');
        $statusFilter = $request->query('status');

        $riwayatQuery = Peminjaman::with(['user', 'alat', 'pengembalian.denda'])
            ->whereHas('pengembalian');

        $this->applyRiwayatFilters($riwayatQuery, $search, $statusFilter);

        $riwayat = $riwayatQuery
            ->leftJoin('pengembalian', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->orderByDesc('pengembalian.tanggal_kembali')
            ->orderByDesc('peminjaman.tanggal_kembali')
            ->select('peminjaman.*')
            ->get();

        $alatIds = $riwayat->pluck('alat_id')->filter()->unique();
        $alatStats = Peminjaman::with('user')
            ->whereIn('alat_id', $alatIds)
            ->get()
            ->groupBy('alat_id')
            ->map(function ($records) {
                $peminjamSummary = $records
                    ->groupBy('user_id')
                    ->map(function ($perUserRecords) {
                        $nama = optional($perUserRecords->first()->user)->name ?? 'Tidak diketahui';
                        return $nama . ' (' . $perUserRecords->count() . 'x)';
                    })
                    ->values()
                    ->implode(', ');

                return [
                    'total' => $records->count(),
                    'peminjam' => $peminjamSummary,
                ];
            });

        $generatedAt = now();
        $fileName = 'riwayat-peminjaman-' . $generatedAt->format('Ymd-His') . '.xls';

        $html = view('admin.peminjaman.exports.riwayat_excel', [
            'riwayat' => $riwayat,
            'alatStats' => $alatStats,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'generatedAt' => $generatedAt,
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
            'Pragma' => 'public',
            'Expires' => '0',
        ]);
    }

    private function applyRiwayatFilters(Builder $riwayatQuery, ?string $search, ?string $statusFilter): void
    {

        if ($search) {
            $riwayatQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('alat', function ($alatQuery) use ($search) {
                    $alatQuery->where('nama_alat', 'like', '%' . $search . '%');
                })->orWhereHas('pengembalian', function ($pengembalianQuery) use ($search) {
                    $pengembalianQuery->where('status', 'like', '%' . $search . '%');
                });
            });
        }

        if ($statusFilter && $statusFilter !== 'all') {
            if ($statusFilter === 'menunggu_konfirmasi') {
                $riwayatQuery->where(function ($query) {
                    $query->whereHas('pengembalian', function ($pengembalianQuery) {
                        $pengembalianQuery->whereRaw('LOWER(status) = ?', ['menunggu']);
                    })->orWhere(function ($subQuery) {
                        $subQuery->whereHas('pengembalian', function ($pengembalianQuery) {
                            $pengembalianQuery
                                ->whereRaw('LOWER(status) LIKE ?', ['%rusak%'])
                                ->orWhereRaw('LOWER(status) LIKE ?', ['%hilang%'])
                                ->orWhereRaw('LOWER(status) LIKE ?', ['%terlambat%']);
                        })->whereDoesntHave('pengembalian.denda', function ($dendaQuery) {
                            $dendaQuery->where('status_bayar', 'lunas');
                        });
                    });
                });
            } elseif ($statusFilter === 'dikembalikan') {
                $riwayatQuery->whereHas('pengembalian', function ($pengembalianQuery) {
                    $pengembalianQuery->whereRaw('LOWER(status) LIKE ?', ['%dikembalikan%']);
                });
            } else {
                $riwayatQuery->whereHas('pengembalian', function ($pengembalianQuery) use ($statusFilter) {
                    $pengembalianQuery->where('status', $statusFilter);
                });
            }
        }
    }

    private function authorizeRiwayatAccess(): void
    {
        $role = strtolower((string) optional(Auth::user())->akun_role);
        if (!in_array($role, ['admin', 'petugas'], true)) {
            abort(403, 'Unauthorized');
        }
    }
}
