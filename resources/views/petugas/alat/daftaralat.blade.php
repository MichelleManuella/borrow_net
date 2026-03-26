@extends('layouts.admin')

@section('page-title', 'Daftar Alat')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Alat</h5>
            <form action="{{ route('petugas.alat.index') }}" method="GET" class="d-flex">
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
                        <td>{{ $loop->iteration }}</td>
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
    </div>
@endsection
