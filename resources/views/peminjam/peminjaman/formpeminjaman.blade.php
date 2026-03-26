@extends('layouts.admin')

@section('page-title', 'Daftar Peminjaman')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Peminjaman</h5>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Alat</th>
                    <th>Jumlah</th>
                    <th>Keperluan</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                        <td>{{ $p->jumlah }}</td>
                        <td>{{ $p->keperluan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($p->status) }}</td>
                        <td>
                            @if (strtolower($p->status) === 'menunggu')
                                <form method="POST" action="{{ route('peminjam.peminjaman.batal', $p->id) }}"
                                    onsubmit="return confirm('Batalkan peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Batal Pinjam
                                    </button>
                                </form>
                            @elseif (strtolower($p->status) === 'ditolak')
                                Pinjaman ditolak
                            @elseif (strtolower($p->status) === 'disetujui' && !$p->pengembalian)
                                <a href="{{ route('peminjam.pengembalian.index', ['peminjaman_id' => $p->id]) }}"
                                    class="btn btn-sm btn-primary">
                                    Kembalikan
                                </a>
                            @else
                                Dikembalikan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data peminjaman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
