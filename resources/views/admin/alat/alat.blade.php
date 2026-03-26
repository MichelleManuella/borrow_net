@extends('layouts.admin')

@section('page-title', 'Data Alat')

@section('content')

    <div class="card-stat">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
            <form action="{{ route('admin.alat.index') }}" method="GET" class="d-flex w-100" style="max-width: 360px;">
                <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm"
                    placeholder="Cari nama alat atau kategori">
            </form>
            <a href="{{ route('admin.alat.create') }}" class="btn btn-primary btn-sm ms-md-3">
                + Tambah Alat
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alats as $alat)
                    <tr>
                        <td>{{ ($alats->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $alat->nama_alat }}</td>
                        <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            <span
                                class="badge 
                            {{ $alat->kondisi == 'Baik' ? 'bg-success' : 'bg-danger' }}">
                                {{ $alat->kondisi }}
                            </span>
                        </td>
                        <td>{{ $alat->stok }}</td>
                        <td>
                            <a href="{{ route('admin.alat.edit', $alat->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus alat ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Data alat belum ada
                        </td>
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
