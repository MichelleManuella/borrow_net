@extends('layouts.admin')

@section('page-title', 'Daftar Peminjaman')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Peminjaman</h5>
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
                @forelse ($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($p->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data peminjaman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
