<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --dark: #060608;
            --primary: #2370A1;
            --purple: #a495c6;
            --peach: #fad3ce;
        }
        
        /* Background */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        
        /* Card */
        .auth-card {
            border: none;
            border-radius: 22px;
            padding: 35px;
            background: #ffffff;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        /* Title */
        .auth-title {
            font-weight: 700;
            font-size: 26px;
            color: var(--dark);
            margin-bottom: 25px;
        }
        
        /* Input */
        .form-control {
            border-radius: 14px;
            padding: 12px;
            border: 1px solid #ddd;
            transition: 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(35,112,161,0.2);
        }
        
        /* Button */
        .btn-auth-primary {
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border: none;
            transition: 0.3s ease;
        }
        
        .btn-auth-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Link */
        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-link:hover {
            text-decoration: underline;
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