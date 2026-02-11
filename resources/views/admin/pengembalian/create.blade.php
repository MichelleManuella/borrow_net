@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Pengembalian</h1>

    <form action="{{ route('admin.pengembalian.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Peminjaman</label>
            <select name="peminjaman_id" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach($peminjaman as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->user->name }} - {{ $p->alat->nama_alat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="dikembalikan tepat waktu">Tepat Waktu</option>
                <option value="dikembalikan terlambat">Terlambat</option>
                <option value="alat rusak">Alat Rusak</option>
                <option value="alat hilang">Alat Hilang</option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.pengembalian.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
