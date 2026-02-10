@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Peminjaman</h1>

    <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Peminjam</label>
            <select name="user_id" class="form-control" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $peminjaman->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Alat</label>
            <select name="alat_id" class="form-control" required>
                <option value="">-- Pilih Alat --</option>
                @foreach($alats as $alat)
                    <option value="{{ $alat->id }}" {{ $alat->id == $peminjaman->alat_id ? 'selected' : '' }}>
                        {{ $alat->nama_alat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ $peminjaman->tanggal_pinjam }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" value="{{ $peminjaman->tanggal_kembali }}" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
