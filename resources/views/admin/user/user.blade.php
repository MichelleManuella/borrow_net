@extends('layouts.admin')

@section('page-title', 'Data User')

@section('content')

<div class="card-stat">
    <div class="d-flex justify-content-between mb-3">
        <h5>Daftar User</h5>
        <a href="{{ route('user.create') }}" class="btn btn-primary">
            + Tambah User
        </a>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Akun Role</th>
                <th>Role</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->akun_role) }}</td>
                    <td>{{ $user->role ?? '-' }}</td>
                    <td>

                    <a href="{{ route('user.edit', $user->id) }}"
                       class="btn btn-sm btn-warning">
                         Edit
                    </a>

    <form action="{{ route('user.destroy', $user->id) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Yakin hapus user ini?')">
        @csrf
        @method('DELETE')

        <button class="btn btn-sm btn-danger">
            Hapus
        </button>
    </form>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Data user masih kosong
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
