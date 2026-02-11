@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit Data Pengembalian</h3>

    <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" 
                   value="{{ $pengembalian->tanggal_kembali }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="dikembalikan" 
                    {{ $pengembalian->status == 'Tepat Waktu' ? 'selected' : '' }}>
                    Tepat Waktu
                </option>
                <option value="rusak" 
                    {{ $pengembalian->status == 'Alat Rusak' ? 'selected' : '' }}>
                    Alat Rusak
                </option>
                <option value="terlambat"
                    {{ $pengembalian->status == 'Terlambat' ? 'selected' : '' }}>
                    Terlambat
                </option>
                <option value="terlambat"
                    {{ $pengembalian->status == 'Alat Hilang' ? 'selected' : '' }}>
                    Alat Hilang
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ $pengembalian->keterangan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
