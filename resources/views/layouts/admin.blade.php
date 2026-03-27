<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --deep-forest: #40513B;
            --leaf: #628141;
            --sand: #E5D9B6;
            --ember: #E67E22;
            --ink: #2C2F24;
            --stroke: rgba(64, 81, 59, 0.12);
            --muted: #6b715c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at 20% 20%, rgba(98, 129, 65, 0.08), transparent 28%),
                radial-gradient(circle at 80% 0%, rgba(230, 126, 34, 0.08), transparent 30%),
                var(--sand);
            color: var(--ink);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            width: 270px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--deep-forest), var(--leaf));
            color: #f9f9f4;
            padding: 28px 22px;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
        }

        .sidebar h4 {
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            color: #fffdf4;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: #f9f9f4;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: rgba(229, 217, 182, 0.14);
            border-color: rgba(229, 217, 182, 0.35);
            color: #fffdf4;
        }

        .sidebar .btn {
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid rgba(229, 217, 182, 0.35);
        }

        .content {
            padding: 32px;
            width: 100%;
            background: linear-gradient(180deg, rgba(64, 81, 59, 0.025), rgba(64, 81, 59, 0.04));
        }

        h4 {
            color: var(--deep-forest);
            font-weight: 700;
        }

        .card-stat {
            border-radius: 18px;
            padding: 20px;
            background: #fdfbf5;
            border: 1px solid var(--stroke);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .card,
        .table,
        .form-control,
        .form-select,
        .btn,
        .alert {
            border-radius: 14px;
        }

        .table thead {
            background: rgba(64, 81, 59, 0.08);
        }

        .table th {
            color: var(--deep-forest);
            font-weight: 700;
            border-bottom: 0;
        }

        .table td {
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(98, 129, 65, 0.05);
        }

        .badge.bg-success {
            background: var(--leaf) !important;
        }

        .badge.bg-danger {
            background: #c44121 !important;
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--leaf), var(--deep-forest));
            border: 1px solid var(--leaf);
            box-shadow: 0 10px 24px rgba(98, 129, 65, 0.25);
        }

        .btn-warning {
            background: linear-gradient(120deg, var(--ember), #f1a245);
            border: none;
            color: #3f2a12;
        }

        .btn-outline-secondary,
        .btn-secondary {
            color: var(--deep-forest);
            border: 1px solid var(--stroke);
            background: #fffdf4;
        }

        .btn-danger {
            background: #c44121;
            border: none;
        }

        .alert-success {
            background: rgba(98, 129, 65, 0.12);
            border-color: rgba(98, 129, 65, 0.25);
            color: var(--deep-forest);
        }

        .alert-danger {
            background: rgba(196, 65, 33, 0.1);
            border-color: rgba(196, 65, 33, 0.22);
        }

        .pagination .page-link {
            color: var(--deep-forest);
            border-radius: 10px;
            border-color: var(--stroke);
        }

        .pagination .active .page-link {
            background: var(--leaf);
            border-color: var(--leaf);
            color: #fffdf4;
            box-shadow: 0 6px 16px rgba(98, 129, 65, 0.3);
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                z-index: 10;
            }

            .content {
                padding: 24px 18px;
            }
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const palette = {
                bg: '#fdfbf5',
                text: 'var(--ink)',
                confirm: 'var(--leaf)',
                cancel: '#b0b5a3',
                danger: '#c44121'
            };

            const flash = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info')),
                status: @json(session('status')),
            };

            const showFlash = (type, message) => {
                const titles = {
                    success: 'Berhasil',
                    error: 'Terjadi Kesalahan',
                    warning: 'Perhatian',
                    info: 'Informasi'
                };

                Swal.fire({
                    title: titles[type] || 'Info',
                    text: message,
                    icon: type === 'error' ? 'error' : type,
                    background: palette.bg,
                    color: palette.text,
                    confirmButtonColor: palette.confirm,
                    cancelButtonColor: palette.cancel,
                    showConfirmButton: true,
                    timer: type === 'success' ? 1800 : undefined,
                    timerProgressBar: type === 'success',
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        title: 'fw-bold',
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
            };

            const flashOrder = ['success', 'error', 'warning', 'info'];
            let shown = false;

            flashOrder.forEach((type) => {
                if (!shown && flash[type]) {
                    showFlash(type, flash[type]);
                    shown = true;
                }
            });

            if (!shown && flash.status) {
                showFlash('success', flash.status);
            }

            document.querySelectorAll('form[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    const message = form.getAttribute('data-confirm') || 'Yakin melanjutkan tindakan ini?';
                    e.preventDefault();

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        background: palette.bg,
                        color: palette.text,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjut',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: palette.confirm,
                        cancelButtonColor: palette.cancel,
                        customClass: {
                            popup: 'rounded-4 shadow-lg',
                            confirmButton: 'btn btn-success me-2',
                            cancelButton: 'btn btn-outline-secondary'
                        },
                        buttonsStyling: false,
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>
