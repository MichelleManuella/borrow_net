@extends('layouts.admin')

@section('page-title', 'Tambah User')

@section('content')

<div class="card-stat" style="max-width:600px">
    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control" required>
</div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Akun Role</label>
            <select name="akun_role" class="form-select" required>
                <option value="">-- pilih --</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="peminjam">Peminjam</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Role (guru / siswa)</label>
            <select name="role" class="form-select">
                <option value="">-- opsional --</option>
                <option value="guru">Guru</option>
                <option value="siswa">Siswa</option>
                <option value="petugas">Petugas</option>
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
