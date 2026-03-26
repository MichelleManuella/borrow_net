@extends('layouts.admin')

@section('page-title', 'Daftar Alat')

@section('content')
    <div class="card-stat">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
            <form action="{{ route('petugas.alat.index') }}" method="GET" class="d-flex w-100" style="max-width: 360px;">
                <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm"
                    placeholder="Cari nama alat atau kategori">
            </form>
        </div>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alats as $alat)
                    <tr>
                        <td>{{ ($alats->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $alat->nama_alat }}</td>
                        <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $alat->kondisi == 'Baik' ? 'bg-success' : 'bg-danger' }}">
                                {{ $alat->kondisi }}
                            </span>
                        </td>
                        <td>{{ $alat->stok }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Data alat belum ada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $alats->firstItem() ?? 0 }}-{{ $alats->lastItem() ?? 0 }} dari
                {{ $alats->total() }} data</small>
            {{ $alats->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
