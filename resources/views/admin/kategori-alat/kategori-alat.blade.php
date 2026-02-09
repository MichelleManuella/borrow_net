<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background-color: #2370A1;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1c5c84;
        }
    </style>
</head>

<body>

    <div class="container mt-5">

        <h4 class="mb-4">📁 Data Kategori</h4>

        <div class="row">
            <!-- FORM KATEGORI -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h6 class="mb-3">Tambah Kategori</h6>

                    <form method="POST" action="{{ route('kategori.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan kategori" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            <!-- TABEL KATEGORI -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h6 class="mb-3">Daftar Kategori</h6>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Kategori</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>
                                    
            <a href="{{ route('kategori.edit', $kategori->id) }}"
               class="btn btn-sm btn-warning">
                      Edit
            </a>
                
            <!-- FORM HAPUS -->
            <form action="{{ route('kategori.destroy', $kategori->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Yakin hapus kategori ini?')">
                 @csrf
                 @method('DELETE')

                <button type="submit" class="btn btn-sm btn-danger">
                   Hapus
                </button>
            </form>
                            </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Data peminjaman tidak ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>