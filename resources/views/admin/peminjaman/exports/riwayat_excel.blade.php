<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            color: #1f2937;
        }

        .report-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0f5132;
            margin-bottom: 4px;
        }

        .meta {
            margin-bottom: 12px;
            color: #374151;
        }

        .meta span {
            display: inline-block;
            margin-right: 18px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #c8d1dc;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #14532d;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }

        .summary {
            margin-top: 10px;
            font-size: 10pt;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <div class="report-title">Laporan Riwayat Peminjaman</div>
    <div class="meta">
        <span><strong>Tanggal Cetak:</strong> {{ $generatedAt->format('d-m-Y H:i') }}</span>
        <span><strong>Filter Pencarian:</strong> {{ $search ?: 'Semua Data' }}</span>
        <span><strong>Filter Status:</strong>
            {{ $statusFilter && $statusFilter !== 'all' ? $statusFilter : 'Semua Status' }}</span>
    </div>

    <table>
        <colgroup>
            <col style="width: 38px;">
            <col style="width: 120px;">
            <col style="width: 140px;">
            <col style="width: 60px;">
            <col style="width: 170px;">
            <col style="width: 110px;">
            <col style="width: 110px;">
            <col style="width: 130px;">
            <col style="width: 170px;">
            <col style="width: 260px;">
        </colgroup>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Keperluan</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Tanggal Pengembalian</th>
                <th>Status Pengembalian</th>
                <th>Total Barang Yang Sudah Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedRiwayat = $riwayat->groupBy(function ($item) {
                    return $item->user_id ?: 'unknown';
                });
            @endphp

            @forelse ($groupedRiwayat as $groupIndex => $groupItems)
                @php
                    $first = $groupItems->first();
                    $rowspan = $groupItems->count();
                    $namaPeminjam = optional($first->user)->name ?? 'Tidak diketahui';
                    $totalBarang = (int) $groupItems->sum('jumlah');
                @endphp

                @foreach ($groupItems as $rowIndex => $p)
                    @php
                        $pengembalianStatus = strtolower($p->pengembalian->status ?? '');
                        $dendaLunas = ($p->pengembalian->denda->status_bayar ?? '') === 'lunas';
                        $needsConfirm =
                            $pengembalianStatus === 'menunggu' ||
                            (!$dendaLunas &&
                                (str_contains($pengembalianStatus, 'rusak') ||
                                    str_contains($pengembalianStatus, 'hilang') ||
                                    str_contains($pengembalianStatus, 'terlambat')));
                        $displayStatus = $needsConfirm ? 'Menunggu konfirmasi' : $p->pengembalian->status ?? '-';
                    @endphp
                    <tr>
                        @if ($rowIndex === 0)
                            <td class="text-center" rowspan="{{ $rowspan }}">{{ $loop->parent->iteration }}</td>
                            <td class="text-center" rowspan="{{ $rowspan }}">{{ $namaPeminjam }}</td>
                        @endif

                        <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                        <td class="text-right">{{ $p->jumlah ?? 0 }}</td>
                        <td>{{ $p->keperluan ?? '-' }}</td>
                        <td class="text-center nowrap">
                            {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center nowrap">
                            {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center nowrap">
                            {{ optional($p->pengembalian)->tanggal_kembali ? \Carbon\Carbon::parse(optional($p->pengembalian)->tanggal_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $displayStatus }}</td>

                        @if ($rowIndex === 0)
                            <td class="text-center" rowspan="{{ $rowspan }}">{{ $totalBarang }}</td>
                        @endif
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data riwayat untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        Total baris data: {{ $riwayat->count() }}
    </div>
</body>

</html>
