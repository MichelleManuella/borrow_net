@extends('layouts.admin')

@section('page-title', 'Data User')

@section('content')

    <div class="card-stat">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-start mb-3 gap-2">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
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
                        <td>{{ ($users->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->akun_role) }}</td>
                        <td>{{ $user->role ?? '-' }}</td>
                        <td>

                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="d-inline"
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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari
                {{ $users->total() }} data</small>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
