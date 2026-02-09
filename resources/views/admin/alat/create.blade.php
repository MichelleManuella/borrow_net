@extends('layouts.admin')

@section('page-title', 'Tambah Alat')

@section('content')

<div class="card-stat" style="max-width:600px">
    <form action="{{ route('alat.store') }}" method="POST">
        @csrf

        <!-- Nama Alat -->
        <div class="mb-3">
            <label class="form-label">Nama Alat</label>
            <input type="text"
                   name="nama_alat"
                   class="form-control"
                   placeholder="Contoh: Laptop Asus"
                   required>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kondisi -->
        <div class="mb-3">
            <label class="form-label">Kondisi</label>
            <select name="kondisi" class="form-select" required>
                <option value="Baik">Baik</option>
                <option value="Rusak">Rusak</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>
            <a href="{{ route('alat.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
