@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Peminjaman</h1>

        <!-- Notifikasi error validasi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
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
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Alat</label>
                <select name="alat_id" class="form-select" required>
                    <option value="">-- Pilih Alat --</option>
                    @foreach ($alats as $alat)
                        <option value="{{ $alat->id }}">{{ $alat->nama_alat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" min="{{ now()->format('Y-m-d') }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" class="form-control" min="{{ now()->format('Y-m-d') }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keperluan</label>
                <textarea name="keperluan" class="form-control" rows="3" placeholder="Keperluan peminjaman"></textarea>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
