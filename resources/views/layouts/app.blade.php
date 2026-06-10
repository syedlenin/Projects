<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'BD Shop'))</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1a1a2e;
            --accent: #e94560;
            --accent-soft: #ff6b6b;
            --surface: #f8f7f4;
            --card-bg: #ffffff;
            --text-main: #1a1a2e;
            --text-muted: #7a7a8c;
            --border: #e8e6e1;
            --success-color: #2ecc71;
            --nav-height: 68px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text-main);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .brand {
            font-family: 'Sora', sans-serif;
        }

        /* ── Navbar ── */
        .navbar-main {
            background: var(--primary);
            height: var(--nav-height);
            box-shadow: 0 2px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff !important;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: var(--accent);
        }

        .nav-link-main {
            color: rgba(255,255,255,0.78) !important;
            font-size: 0.88rem;
            font-weight: 500;
            padding: 0.4rem 0.85rem !important;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-link-main:hover, .nav-link-main.active {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
        }

        .cart-badge {
            background: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 2px;
            vertical-align: top;
        }

        .btn-nav-cta {
            background: var(--accent);
            color: #fff !important;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.42rem 1.1rem !important;
            border-radius: 8px;
            border: none;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-nav-cta:hover {
            background: var(--accent-soft);
            transform: translateY(-1px);
        }

        /* ── Alerts ── */
        .alert-flash {
            border-radius: 10px;
            border: none;
            font-size: 0.9rem;
            padding: 0.85rem 1.2rem;
        }

        /* ── Buttons ── */
        .btn-primary-brand {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            transition: all 0.2s;
        }

        .btn-primary-brand:hover {
            background: #2d2d4e;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            transition: all 0.2s;
        }

        .btn-accent:hover {
            background: var(--accent-soft);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Cards ── */
        .card-clean {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        /* ── Footer ── */
        .footer-main {
            background: var(--primary);
            color: rgba(255,255,255,0.65);
            font-size: 0.85rem;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .footer-main a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
        }

        .footer-main a:hover {
            color: var(--accent);
        }

        /* ── Page wrapper ── */
        .page-content {
            min-height: calc(100vh - var(--nav-height) - 130px);
            padding: 2.5rem 0;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar-main navbar navbar-expand-lg px-3 px-md-4">
    <div class="container-xl">
        <a class="brand navbar-brand me-4" href="{{ route('home') }}">MenzBD<span>Shop</span></a>

        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link-main nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-main nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-grid me-1"></i>Products
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center gap-2">
                @auth
                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link-main nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag"></i>
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Orders --}}
                    <li class="nav-item">
                        <a class="nav-link-main nav-link" href="{{ route('orders.index') }}">
                            <i class="bi bi-receipt me-1"></i>Orders
                        </a>
                    </li>

                    {{-- Admin link --}}
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link-main nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Admin
                            </a>
                        </li>
                    @endif

                    {{-- User dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link-main nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius:10px;">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link-main nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-nav-cta nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ── Flash Messages ── --}}
@if(session('success') || session('error') || session('info'))
<div class="container-xl pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-flash alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-flash alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-flash alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ── Page Content ── --}}
<main class="page-content">
    @yield('content')
</main>

{{-- ── Footer ── --}}
<footer class="footer-main">
    <div class="container-xl">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0"><span class="fw-600 text-white">Menz BDShop</span> — Bangladeshi E-Commerce Platform</p>
            <p class="mb-0">Payments: SSLCommerz · aamarPay · ShurjoPay</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
