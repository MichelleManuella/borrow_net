@extends('layouts.admin')

@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <div class="card-stat">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
            <h5 class="mb-0">Riwayat Peminjaman</h5>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $p)
                    <tr>
                        <td>{{ ($riwayat->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($p->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada riwayat peminjaman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $riwayat->firstItem() ?? 0 }}-{{ $riwayat->lastItem() ?? 0 }} dari
                {{ $riwayat->total() }} data</small>
            {{ $riwayat->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
