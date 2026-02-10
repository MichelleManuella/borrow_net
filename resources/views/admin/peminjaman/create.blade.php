@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Peminjaman</h1>

    <!-- Notifikasi error validasi -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.peminjaman.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Peminjam</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alat</label>
            <select name="alat_id" class="form-select" required>
                <option value="">-- Pilih Alat --</option>
                @foreach($alats as $alat)
                    <option value="{{ $alat->id }}">{{ $alat->nama_alat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
