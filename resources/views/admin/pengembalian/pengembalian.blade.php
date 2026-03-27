@extends('layouts.admin')

@section('page-title', 'Data Pengembalian')

@section('content')
    <div class="card-stat">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
            <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary">
                + Tambah Pengembalian
            </a>
        </div>

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
                    <td>{{ ($pengembalian->firstItem() ?? 0) + $loop->index }}</td>
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
                                style="display:inline;" data-confirm="Konfirmasi denda sudah dibayar?">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    Konfirmasi Bayar
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" method="POST"
                            style="display:inline;" data-confirm="Yakin mau hapus data ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan
                {{ $pengembalian->firstItem() ?? 0 }}-{{ $pengembalian->lastItem() ?? 0 }} dari
                {{ $pengembalian->total() }} data</small>
            {{ $pengembalian->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
