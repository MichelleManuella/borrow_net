@extends('layouts.admin')

@section('page-title', 'Edit Alat')

@section('content')

<div class="card-stat" style="max-width:600px">
    <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Alat -->
        <div class="mb-3">
            <label class="form-label">Nama Alat</label>
            <input type="text"
                   name="nama_alat"
                   class="form-control"
                   value="{{ $alat->nama_alat }}"
                   required>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ $alat->kategori_id == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kondisi -->
        <div class="mb-3">
            <label class="form-label">Kondisi</label>
            <select name="kondisi" class="form-select">
                <option value="Baik" {{ $alat->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak" {{ $alat->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Update
            </button>
            <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
