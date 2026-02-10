@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Data Peminjaman</h1>

    <!-- Notifikasi sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol tambah -->
    <div class="mb-3">
        <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Peminjaman
        </a>
    </div>

    <!-- Tabel responsive -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Peminjam</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>

                    <!-- Dropdown status -->
                    <td>
                        <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="menunggu" {{ strtolower($p->status) == 'menunggu' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ strtolower($p->status) == 'disetujui' ? 'selected' : '' }}>Approved</option>
                                <option value="ditolak" {{ strtolower($p->status) == 'ditolak' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                    </td>

                    <!-- Aksi -->
                    <td>
                        <a href="{{ route('admin.peminjaman.edit', $p->id) }}" class="btn btn-sm btn-info mb-1">
                            Edit
                        </a>

                        <form action="{{ route('admin.peminjaman.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-warning mb-1" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
