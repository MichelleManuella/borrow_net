@extends('layouts.admin')

@section('page-title', 'Form Peminjaman')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Form Peminjaman</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peminjam.peminjaman.store') }}" method="POST">
            @csrf
            <input type="hidden" name="alat_id" value="{{ $alat->id }}">

            <div class="mb-3">
                <label class="form-label">Nama Alat</label>
                <input type="text" class="form-control" value="{{ $alat->nama_alat }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="{{ now()->format('Y-m-d') }}"
                    min="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" class="form-control" min="{{ now()->format('Y-m-d') }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Alat</label>
                <input type="number" name="jumlah" class="form-control" min="1" max="{{ $alat->stok }}"
                    value="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keperluan Peminjaman</label>
                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum jaringan"></textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('peminjam.alat.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
@endsection
