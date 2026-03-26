@extends('layouts.admin')

@section('page-title', 'Data Peminjaman')

@section('content')
    <div class="card-stat">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (optional(auth()->user())->akun_role !== 'petugas')
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-start mb-3 gap-2">
                <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Peminjaman
                </a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Keperluan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        <tr>
                            <td>{{ ($peminjaman->firstItem() ?? 0) + $loop->index }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                            <td>{{ $p->jumlah }}</td>
                            <td>{{ $p->keperluan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d-m-Y') }}</td>
                            <td>{{ $p->alat->stok ?? '-' }}</td>

                            <!-- Dropdown status -->
                            <td>
                                <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="menunggu"
                                            {{ strtolower($p->status) == 'menunggu' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="disetujui"
                                            {{ strtolower($p->status) == 'disetujui' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="ditolak"
                                            {{ strtolower($p->status) == 'ditolak' ? 'selected' : '' }}>Rejected
                                        </option>
                                    </select>
                                </form>
                            </td>

                            <!-- Aksi -->
                            <td>
                                <a href="{{ route('admin.peminjaman.edit', $p->id) }}" class="btn btn-sm btn-info mb-1">
                                    Edit
                                </a>

                                <form action="{{ route('admin.peminjaman.destroy', $p->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-warning mb-1"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan
                {{ $peminjaman->firstItem() ?? 0 }}-{{ $peminjaman->lastItem() ?? 0 }}
                dari {{ $peminjaman->total() }} data</small>
            {{ $peminjaman->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
