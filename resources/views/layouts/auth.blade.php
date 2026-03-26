<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --deep-forest: #40513B;
            --leaf: #628141;
            --sand: #E5D9B6;
            --ember: #E67E22;
            --ink: #2C2F24;
            --stroke: rgba(64, 81, 59, 0.14);
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background:
                radial-gradient(circle at 18% 22%, rgba(98, 129, 65, 0.16), transparent 32%),
                radial-gradient(circle at 82% 12%, rgba(230, 126, 34, 0.12), transparent 36%),
                radial-gradient(circle at 50% 80%, rgba(64, 81, 59, 0.08), transparent 42%),
                linear-gradient(160deg, #f9f4e5 0%, #e9dfc3 48%, #d8cfb0 100%);
            color: var(--ink);
        }

        .auth-card {
            border: 1px solid var(--stroke);
            border-radius: 22px;
            padding: 36px;
            background: #fdfbf5;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            max-width: 520px;
            margin: 0 auto;
        }

        .auth-title {
            font-weight: 700;
            font-size: 28px;
            color: var(--deep-forest);
            margin-bottom: 10px;
        }

        .auth-description {
            color: #505744;
            font-size: 15px;
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            color: var(--deep-forest);
        }

        .form-control {
            border-radius: 14px;
            padding: 12px;
            border: 1px solid var(--stroke);
            background: #fffdf4;
            transition: 0.25s ease;
        }

        .form-control:focus {
            border-color: var(--leaf);
            box-shadow: 0 0 0 4px rgba(98, 129, 65, 0.18);
        }

        .btn-auth-primary {
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
            background: linear-gradient(120deg, var(--leaf), var(--deep-forest));
            border: 1px solid var(--leaf);
            color: #fffdf4;
            box-shadow: 0 12px 28px rgba(98, 129, 65, 0.28);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn-auth-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(98, 129, 65, 0.32);
        }

        .auth-link {
            color: var(--deep-forest);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            color: var(--ember);
            text-decoration: underline;
        }

        .alert-danger {
            border-radius: 14px;
            border: 1px solid rgba(230, 126, 34, 0.22);
            background: rgba(230, 126, 34, 0.12);
            color: var(--deep-forest);
        }

        .auth-page-wrapper {
            width: min(560px, 100%);
            padding: 0 16px;
        }
    </style>

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-page-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
