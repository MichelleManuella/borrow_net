@extends('layouts.admin')

@section('page-title', 'Data Alat')

@section('content')

<div class="card-stat">
    <div class="d-flex justify-content-between mb-3">
        <h5>Daftar Alat</h5>
        <a href="{{ route('alat.create') }}" class="btn btn-primary btn-sm">
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alats as $alat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $alat->nama_alat }}</td>
                    <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                    <td>
                        <span class="badge 
                            {{ $alat->kondisi == 'Baik' ? 'bg-success' : 'bg-danger' }}">
                            {{ $alat->kondisi }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('alat.edit', $alat->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('alat.destroy', $alat->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus alat ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data alat belum ada
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
