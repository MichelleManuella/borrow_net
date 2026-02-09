@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')

<div class="card-stat col-md-6">
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name"
                   class="form-control"
                   value="{{ old('name', $user->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control"
                   value="{{ old('email', $user->email) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password (Opsional)</label>
            <input type="password" name="password"
                   class="form-control"
                   placeholder="Kosongkan jika tidak diubah">
        </div>

        <div class="mb-3">
            <label class="form-label">Akun Role</label>
            <select name="akun_role" class="form-select" required>
                <option value="admin" {{ $user->akun_role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ $user->akun_role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="peminjam" {{ $user->akun_role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="">-</option>
                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

@endsection
