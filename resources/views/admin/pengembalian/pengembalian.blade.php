@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Data Pengembalian</h1>

        <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary mb-3">
            + Tambah Pengembalian
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>

            @forelse($pengembalian as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->peminjaman->user->name }}</td>
                    <td>{{ $p->peminjaman->alat->nama_alat }}</td>
                    <td>{{ $p->tanggal_kembali }}</td>
                    <td>{{ $p->status }}</td>
                    @php
                        $statusLower = strtolower($p->status ?? '');
                        $needsDenda =
                            str_contains($statusLower, 'terlambat') ||
                            str_contains($statusLower, 'rusak') ||
                            str_contains($statusLower, 'hilang');
                        $dendaNominal = $p->denda->nominal ?? 0;
                        $dendaStatus = $p->denda->status_bayar ?? 'menunggu';
                    @endphp
                    <td>
                        @if ($needsDenda)
                            Rp{{ number_format($dendaNominal, 0, ',', '.') }}
                            <div class="text-muted" style="font-size: 0.85rem;">
                                {{ $dendaStatus === 'lunas' ? 'Lunas' : 'Menunggu bayar' }}
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $p->keterangan ?? '-' }}</td>

                    <td>
                        <a href="{{ route('admin.pengembalian.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        @if ($needsDenda && $dendaStatus !== 'lunas')
                            <form action="{{ route('admin.pengembalian.konfirmasi', $p->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Konfirmasi denda sudah dibayar?')">
                                    Konfirmasi Bayar
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin mau hapus data ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Belum ada data pengembalian
                    </td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
