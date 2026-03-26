@extends('layouts.admin')

@section('content')
    <div class="container">
        <h3>Edit Data Pengembalian</h3>

        <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" class="form-control" value="{{ $peminjaman->user->name }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alat</label>
                    <input type="text" class="form-control" value="{{ $peminjaman->alat->nama_alat }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" value="{{ $peminjaman->tanggal_pinjam }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Jatuh Tempo</label>
                    <input type="date" class="form-control" value="{{ $peminjaman->tanggal_kembali }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" class="form-control"
                        value="{{ $pengembalian->tanggal_kembali }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                @php
                    $statusLower = strtolower($pengembalian->status ?? '');
                @endphp
                <select name="status" class="form-select" required>
                    <option value="menunggu" {{ $statusLower === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dikembalikan tepat waktu" {{ str_contains($statusLower, 'tepat') ? 'selected' : '' }}>
                        Tepat Waktu</option>
                    <option value="dikembalikan terlambat" {{ str_contains($statusLower, 'terlambat') ? 'selected' : '' }}>
                        Terlambat</option>
                    <option value="alat rusak" {{ str_contains($statusLower, 'rusak') ? 'selected' : '' }}>Alat Rusak
                    </option>
                    <option value="alat hilang" {{ str_contains($statusLower, 'hilang') ? 'selected' : '' }}>Alat Hilang
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nominal Denda</label>
                <input type="number" name="nominal_denda" class="form-control" id="nominal-denda-input"
                    value="{{ old('nominal_denda', $pengembalian->denda->nominal ?? $defaultDenda) }}" min="0">
                <div class="form-text">Default otomatis sesuai aturan, tapi bisa diubah manual.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Pembayaran</label>
                <select name="status_bayar" class="form-select">
                    <option value="menunggu"
                        {{ ($pengembalian->denda->status_bayar ?? 'menunggu') === 'menunggu' ? 'selected' : '' }}>
                        Menunggu
                    </option>
                    <option value="lunas" {{ ($pengembalian->denda->status_bayar ?? '') === 'lunas' ? 'selected' : '' }}>
                        Lunas</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control">{{ $pengembalian->keterangan }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script>
        const statusSelect = document.querySelector('select[name="status"]');
        const nominalInput = document.getElementById('nominal-denda-input');
        const jatuhTempo = new Date('{{ $peminjaman->tanggal_kembali }}');
        const tanggalPengembalian = new Date('{{ $pengembalian->tanggal_kembali }}');
        const dendaRusak = 50000;
        const dendaHilang = 200000;
        const dendaTerlambatPerHari = 1000;

        const calculateDenda = (statusValue) => {
            const statusLower = statusValue.toLowerCase();
            if (statusLower.includes('rusak')) {
                return dendaRusak;
            }
            if (statusLower.includes('hilang')) {
                return dendaHilang;
            }
            if (statusLower.includes('terlambat')) {
                const diffMs = tanggalPengembalian - jatuhTempo;
                const diffDays = Math.max(0, Math.ceil(diffMs / (1000 * 60 * 60 * 24)));
                return diffDays * dendaTerlambatPerHari;
            }
            return 0;
        };

        const updateNominal = () => {
            const computed = calculateDenda(statusSelect.value);
            nominalInput.value = computed;
        };

        statusSelect.addEventListener('change', updateNominal);

        if (!nominalInput.value) {
            updateNominal();
        }
    </script>
@endsection
