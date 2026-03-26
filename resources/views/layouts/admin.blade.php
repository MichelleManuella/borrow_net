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
            background: rgba(255, 255, 255, 0.2);
        }

        .content {
            padding: 30px;
        }

        .card-stat {
            border-radius: 18px;
            padding: 20px;
            background: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <h4>📦 BORROW NET</h4>
            @php
                $akunRole = optional(auth()->user())->akun_role;
            @endphp

            @if ($akunRole === 'petugas')
                <a href="{{ route('petugas.dashboard') }}"
                    class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <a href="{{ route('petugas.alat.index') }}"
                    class="{{ request()->routeIs('petugas.alat.*') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i> Daftar Alat
                </a>

                <a href="{{ route('admin.peminjaman.index') }}"
                    class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Peminjaman
                </a>

                <a href="{{ route('petugas.pengembalian.index') }}"
                    class="{{ request()->routeIs('petugas.pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left"></i> Pengembalian
                </a>

                <a href="{{ route('admin.peminjaman.riwayat') }}"
                    class="{{ request()->routeIs('admin.peminjaman.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                </a>
            @elseif ($akunRole === 'peminjam')
                <div class="mb-3 p-3" style="background: rgba(255, 255, 255, 0.12); border-radius: 12px;">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">{{ auth()->user()->email }}</div>
                </div>

                <a href="{{ route('peminjam.dashboard') }}"
                    class="{{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <a href="{{ route('peminjam.alat.index') }}"
                    class="{{ request()->routeIs('peminjam.alat.*') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i> Daftar Alat
                </a>

                <a href="{{ route('peminjam.peminjaman.index') }}"
                    class="{{ request()->routeIs('peminjam.peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Daftar Peminjaman
                </a>

                <a href="{{ route('peminjam.riwayat.index') }}"
                    class="{{ request()->routeIs('peminjam.riwayat.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <a href="{{ route('admin.user.index') }}"
                    class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> User
                </a>

                <a href="{{ route('admin.alat.index') }}"
                    class="{{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i> Alat
                </a>

                <a href="{{ route('admin.kategori.index') }}"
                    class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>

                <a href="{{ route('admin.peminjaman.index') }}"
                    class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Data Peminjaman
                </a>

                <a href="{{ route('admin.pengembalian.index') }}"
                    class="{{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left"></i> Pengembalian
                </a>

                <a href="{{ route('admin.peminjaman.riwayat') }}"
                    class="{{ request()->routeIs('admin.peminjaman.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                </a>

                <a href="{{ route('admin.log.log') }}"
                    class="{{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Log Aktivitas
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-light w-100">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>

        </div>

        <!-- CONTENT -->
        <div class="flex-grow-1 content">
            <h4 class="mb-4">@yield('page-title')</h4>
            @yield('content')
        </div>
    </div>

</body>

</html>
