@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="mb-0">Riwayat Peminjaman</h1>
            <form method="GET" action="{{ route('admin.peminjaman.riwayat') }}" class="d-flex gap-2" id="riwayat-filter-form">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, alat, status"
                    value="{{ request('q') }}">
                <select name="status" class="form-select"
                    onchange="document.getElementById('riwayat-filter-form').submit()">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>
                        Menunggu Konfirmasi</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                    </option>
                    <option value="dikembalikan tepat waktu"
                        {{ request('status') === 'dikembalikan tepat waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="dikembalikan terlambat"
                        {{ request('status') === 'dikembalikan terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="alat rusak" {{ request('status') === 'alat rusak' ? 'selected' : '' }}>Alat Rusak
                    </option>
                    <option value="alat hilang" {{ request('status') === 'alat hilang' ? 'selected' : '' }}>Alat Hilang
                    </option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('admin.peminjaman.riwayat') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Keperluan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $p)
                        <tr>
                            <td>{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                            <td>{{ $p->jumlah }}</td>
                            <td>{{ $p->keperluan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse(optional($p->pengembalian)->tanggal_kembali)->format('d-m-Y') }}
                            </td>
                            @php
                                $pengembalianStatus = strtolower($p->pengembalian->status ?? '');
                                $dendaLunas = ($p->pengembalian->denda->status_bayar ?? '') === 'lunas';
                                $needsConfirm =
                                    $pengembalianStatus === 'menunggu' ||
                                    (!$dendaLunas &&
                                        (str_contains($pengembalianStatus, 'rusak') ||
                                            str_contains($pengembalianStatus, 'hilang') ||
                                            str_contains($pengembalianStatus, 'terlambat')));
                                $displayStatus = $needsConfirm
                                    ? 'Menunggu konfirmasi'
                                    : $p->pengembalian->status ?? '-';
                                $statusLower = strtolower($displayStatus);
                                if ($needsConfirm) {
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif (
                                    str_contains($statusLower, 'tepat') ||
                                    str_contains($statusLower, 'dikembalikan')
                                ) {
                                    $badgeClass = 'bg-success';
                                } elseif (str_contains($statusLower, 'rusak') || str_contains($statusLower, 'hilang')) {
                                    $badgeClass = 'bg-danger';
                                } elseif (str_contains($statusLower, 'terlambat')) {
                                    $badgeClass = 'bg-warning text-dark';
                                } else {
                                    $badgeClass = 'bg-secondary';
                                }
                            @endphp
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $displayStatus }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada riwayat peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $riwayat->links() }}
        </div>
    </div>
@endsection
