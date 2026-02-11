<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: #2370A1;
            border: none;
        }
    </style>
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card p-4">
        <h5 class="mb-3">✏️ Edit Kategori</h5>

        <form method="POST" action="{{ route('admin.kategori.update', $kategori->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text"
                       name="nama_kategori"
                       class="form-control"
                       value="{{ $kategori->nama_kategori }}"
                       required>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary w-100">
                    Update
                </button>

                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary w-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
