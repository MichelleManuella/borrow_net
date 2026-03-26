@extends('layouts.admin')

@section('page-title', 'Daftar Alat')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Alat</h5>
        </div>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data alat belum ada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
