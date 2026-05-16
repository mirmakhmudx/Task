<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, body { font-family: 'DM Sans', sans-serif; }
        body { background: #f8f9fb; color: #1a1a2e; }

        /* Navbar */
        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e8eaf0;
            padding: 0.85rem 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1a1a2e !important;
            letter-spacing: -0.3px;
        }
        .nav-link {
            color: #4a4a6a !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 0.85rem !important;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link:hover { background: #f0f1f5; color: #1a1a2e !important; }
        .dropdown-menu {
            border: 1px solid #e8eaf0;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 6px;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.5rem 0.85rem;
            color: #4a4a6a;
        }
        .dropdown-item:hover { background: #f0f1f5; color: #1a1a2e; }
        .dropdown-divider { border-color: #e8eaf0; margin: 4px 0; }

        /* Cards */
        .card {
            border: 1px solid #e8eaf0;
            border-radius: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            background: #fff;
        }
        .card-body { padding: 1.75rem; }

        /* Nav tabs */
        .nav-tabs { border-bottom: 1px solid #e8eaf0; }
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            border-radius: 0;
            color: #6b7280 !important;
            padding: 0.6rem 1rem !important;
            margin-bottom: -1px;
        }
        .nav-tabs .nav-link.active {
            color: #1a1a2e !important;
            border-bottom-color: #1a1a2e;
            background: transparent;
        }

        /* Buttons */
        .btn-primary {
            background: #1a1a2e;
            border-color: #1a1a2e;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1.25rem;
            transition: opacity 0.15s;
        }
        .btn-primary:hover { background: #2d2d4e; border-color: #2d2d4e; opacity: 0.92; }
        .btn-outline-primary {
            color: #1a1a2e;
            border-color: #d1d5db;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1.25rem;
        }
        .btn-outline-primary:hover { background: #f0f1f5; color: #1a1a2e; border-color: #d1d5db; }

        /* Footer */
        footer {
            border-top: 1px solid #e8eaf0!important;
            color: #9ca3af;
            font-size: 0.82rem;
        }

        main { min-height: calc(100vh - 130px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adverts.*') ? 'fw-bold' : '' }}"
                           href="{{ route('adverts.index') }}">
                            Adverts
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ Auth::user()?->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.home') }}">Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('cabinet.index') }}">Cabinet</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <footer class="py-3 mt-4 text-center">
        <small>&copy; {{ date('Y') }} - {{ config('app.name', 'Laravel') }}</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(["resources/js/app.js"])
</body>
</html>
