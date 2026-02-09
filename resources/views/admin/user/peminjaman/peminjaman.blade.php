<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman</title>
</head>
<body>
    <h1>Halaman Peminjaman</h1>
    
    @if($peminjaman->count() > 0)
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->user->name ?? 'N/A' }}</td>
                    <td>{{ $p->alat->nama_alat ?? 'N/A' }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $p->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data peminjaman</p>
    @endif
</body>
</html>