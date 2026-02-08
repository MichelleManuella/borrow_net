<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --dark: #060608;
            --primary: #2370A1;
            --purple: #a495c6;
            --peach: #fad3ce;
        }

        body {
            background: #f6f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--purple));
            color: white;
            padding: 25px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        .content {
            padding: 30px;
        }

        .card-stat {
            border-radius: 18px;
            padding: 20px;
            background: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4>📦 BORROW NET</h4>

        <a href="{{ route('admin.dashboard') }}" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="#"><i class="bi bi-people"></i> User</a>
        <a href="#"><i class="bi bi-laptop"></i> Alat</a>
        <a href="{{ route('kategori.index') }}">
        <i class="bi bi-tags"></i> Kategori </a>
        <a href="#"><i class="bi bi-arrow-left-right"></i> Data Peminjaman</a>
        <a href="#"><i class="bi bi-arrow-repeat"></i> Data Pengembalian</a>
        <a href="#"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 content">
        <h4 class="mb-4">Dashboard Overview</h4>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-stat" style="background:linear-gradient(135deg,#fad3ce,#fff)">
                    <small>Total User</small>
                    <h3>120</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat" style="background:linear-gradient(135deg,#dceeff,#fff)">
                    <small>Total Alat</small>
                    <h3>45</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat" style="background:linear-gradient(135deg,#ede7f6,#fff)">
                    <small>Peminjaman Aktif</small>
                    <h3>18</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat">
                    <small>Pengembalian Hari Ini</small>
                    <h3>7</h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card-stat">
                    <h6>Statistik Peminjaman</h6>
                    <p class="text-muted">(Chart.js nanti)</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-stat">
                    <h6>Log Aktivitas Terbaru</h6>
                    <ul>
                        <li>Admin menambah Laptop</li>
                        <li>Siswa meminjam Proyektor</li>
                        <li>Guru mengembalikan Laptop</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
