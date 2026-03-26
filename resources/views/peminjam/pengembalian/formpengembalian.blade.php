@extends('layouts.admin')

@section('page-title', 'Pengembalian')

@section('content')
    <div class="card-stat">
        <div class="d-flex justify-content-between mb-3">
            <h5>Form Pengembalian</h5>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peminjam.pengembalian.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Peminjaman</label>
                    <select name="peminjaman_id" class="form-control" required>
                        <option value="">Pilih alat</option>
                        @foreach ($peminjaman as $p)
                            <option value="{{ $p->id }}"
                                {{ (string) $p->id === (string) $selectedPeminjamanId ? 'selected' : '' }}>
                                {{ $p->alat->nama_alat }} ({{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika ada"></textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Kirim Pengembalian</button>
            </div>
        </form>

        <h6 class="mb-3">Peminjaman yang Bisa Dikembalikan</h6>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->alat->nama_alat }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($p->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada peminjaman yang bisa dikembalikan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
