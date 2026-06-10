<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-bg: #0f0f1a;
            --sidebar-active: #1a1a2e;
            --accent: #e94560;
            --surface: #f3f2ef;
            --card-bg: #fff;
            --text-main: #1a1a2e;
            --text-muted: #7a7a8c;
            --border: #e8e6e1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text-main);
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Sora', sans-serif; }

        /* ── Sidebar ── */
        .admin-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.6rem 1.4rem 1rem;
            font-family: 'Sora', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand span { color: var(--accent); }

        .sidebar-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 1.2rem 1.4rem 0.4rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.65rem 1.4rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-radius: 0;
            transition: all 0.18s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.05);
        }

        .sidebar-link.active {
            color: #fff;
            background: rgba(233,69,96,0.12);
            border-left-color: var(--accent);
        }

        .sidebar-link i { font-size: 1rem; width: 18px; }

        /* ── Main content ── */
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        .admin-topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 0.9rem 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-topbar h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-main);
            margin: 0;
        }

        .admin-body {
            padding: 2rem 1.8rem;
        }

        /* ── Cards ── */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1;
        }

        .card-clean {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        /* ── Table ── */
        .table-admin thead th {
            font-family: 'Sora', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: var(--text-muted);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1rem;
        }

        .table-admin td {
            font-size: 0.875rem;
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table-admin tbody tr:hover { background: #fafaf8; }

        /* ── Badges ── */
        .badge-status {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.32rem 0.7rem;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .badge-paid    { background: #d1fae5; color: #065f46; }
        .badge-unpaid  { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped { background: #ede9fe; color: #5b21b6; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #f3f4f6; color: #6b7280; }

        /* ── Buttons ── */
        .btn-brand {
            background: var(--text-main);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-brand:hover { background: #2d2d4e; color: #fff; transform: translateY(-1px); }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-accent:hover { background: #ff6b6b; color: #fff; }

        /* ── Forms ── */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 0.9rem;
            padding: 0.55rem 0.85rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,0.1);
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Alert flash ── */
        .alert-flash {
            border-radius: 10px;
            border: none;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="admin-sidebar">
    <div class="sidebar-brand">BD<span>Shop</span> <small style="font-size:0.65rem;color:rgba(255,255,255,0.4);display:block;margin-top:2px;">Admin Panel</small></div>

    <div class="sidebar-label">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-label">Catalog</div>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Products
    </a>

    <div class="sidebar-label">Commerce</div>
    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Orders
    </a>
    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        <i class="bi bi-credit-card"></i> Payments
    </a>

    <div class="sidebar-label">People</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Users
    </a>

    <div class="mt-auto p-3 border-top" style="border-color:rgba(255,255,255,0.07)!important;">
        <a href="{{ route('home') }}" class="sidebar-link" style="padding:0.5rem 0.5rem;">
            <i class="bi bi-arrow-left-circle"></i> Back to Shop
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent" style="color:rgba(255,255,255,0.4);">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="admin-main">
    <div class="admin-topbar">
        <h5>@yield('title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:0.82rem;">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </span>
        </div>
    </div>

    <div class="admin-body">
        @if(session('success'))
            <div class="alert alert-success alert-flash alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-flash alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
