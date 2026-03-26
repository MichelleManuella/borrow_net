@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Data Pengembalian</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengembalian as $p)
                        @php
                            $statusLower = strtolower($p->status ?? '');
                            $needsDenda =
                                str_contains($statusLower, 'terlambat') ||
                                str_contains($statusLower, 'rusak') ||
                                str_contains($statusLower, 'hilang');
                            $dendaNominal = $p->denda->nominal ?? 0;
                            $dendaStatus = $p->denda->status_bayar ?? 'menunggu';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->peminjaman->user->name ?? '-' }}</td>
                            <td>{{ $p->peminjaman->alat->nama_alat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->peminjaman->tanggal_kembali)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                            <td>{{ $p->status }}</td>
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
                            <td>
                                <a href="{{ route('petugas.pengembalian.edit', $p->id) }}" class="btn btn-sm btn-primary">
                                    Proses
                                </a>

                                @if ($needsDenda && $dendaStatus !== 'lunas')
                                    <form method="POST" action="{{ route('petugas.pengembalian.konfirmasi', $p->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Konfirmasi denda sudah dibayar?')">
                                            Konfirmasi Bayar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
