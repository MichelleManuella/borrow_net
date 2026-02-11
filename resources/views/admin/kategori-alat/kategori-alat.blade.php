@extends('layouts.admin')

@section('page-title', 'Data Alat')

@section('content')
<div class="container mt-5">

    <!-- HEADER: BREADCRUMB + BACK -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Kategori
                </li>
            </ol>
        </nav>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- JUDUL -->
    <h4 class="mb-4">📁 Data Kategori</h4>

    <div class="row">
        <!-- FORM -->
        <div class="col-md-4">
            <div class="card p-4">
                <h6 class="mb-3">Tambah Kategori</h6>

                <form method="POST" action="{{ route('admin.kategori.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text"
                               name="nama_kategori"
                               class="form-control"
                               placeholder="Masukkan kategori"
                               required>
                    </div>

                    <button class="btn btn-primary w-100">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <!-- TABEL -->
        <div class="col-md-8">
            <div class="card p-4">
                <h6 class="mb-3">Daftar Kategori</h6>

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kategori</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                   class="btn btn-sm btn-warning">
                                   Edit
                                </a>

                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Data kategori belum ada
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>
@endsection