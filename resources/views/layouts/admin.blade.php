<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>

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

        .sidebar a.active,
        .sidebar a:hover {
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

        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
           <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="#"><i class="bi bi-people"></i> User</a>
        <a href="#"><i class="bi bi-laptop"></i> Alat</a>

        <a href="{{ route('admin.kategori') }}"
           class="{{ request()->routeIs('admin.kategori') ? 'active' : '' }}">
           <i class="bi bi-tags"></i> Kategori
        </a>

        <a href="#"><i class="bi bi-arrow-left-right"></i> Data Peminjaman</a>
        <a href="#"><i class="bi bi-arrow-repeat"></i> Data Pengembalian</a>
        <a href="#"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 content">
        <h4 class="mb-4">@yield('page-title')</h4>
        @yield('content')
    </div>
</div>

</body>
</html>
