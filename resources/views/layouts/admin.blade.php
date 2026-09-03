<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SchoolPay') — Admin</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 260px;
            --navy: #0f1b2d;
            --navy-mid: #162236;
            --navy-light: #1e3352;
            --accent: #f0a500;
            --accent-soft: rgba(240,165,0,.15);
            --text-muted-custom: #8a9bb0;
            --surface: #ffffff;
            --bg: #f4f6fa;
            --border: #e4e9f0;
            --success: #22c55e;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: #1e293b;
            margin: 0;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--navy);
            display: flex; flex-direction: column;
            z-index: 1040;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 18px; margin-right: 10px;
        }
        .sidebar-brand .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 20px; color: #fff; letter-spacing: .3px;
        }
        .sidebar-brand .brand-sub {
            font-size: 11px; color: var(--text-muted-custom);
            margin-top: 2px; display: block;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .nav-section-label {
            font-size: 10px; font-weight: 600;
            letter-spacing: 1.2px; text-transform: uppercase;
            color: var(--text-muted-custom);
            padding: 10px 10px 6px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #9aacbf;
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all .18s ease;
            margin-bottom: 2px;
        }
        .sidebar-link i { font-size: 17px; width: 20px; text-align: center; }
        .sidebar-link:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar-link.active { background: var(--accent-soft); color: var(--accent); }
        .sidebar-link.active i { color: var(--accent); }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.07);
            font-size: 13px; color: var(--text-muted-custom);
        }

        /* ── Topbar ── */
        #topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px;
            z-index: 1030;
            gap: 16px;
        }
        .topbar-title {
            font-size: 18px; font-weight: 600; color: #0f1b2d; flex: 1;
        }
        .topbar-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 20px; padding: 5px 14px;
            font-size: 13px; color: #4b5563;
        }

        /* ── Main ── */
        #main-content {
            margin-left: var(--sidebar-w);
            padding-top: 64px;
            min-height: 100vh;
        }
        .page-inner { padding: 28px 32px; }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            font-weight: 600; font-size: 15px;
        }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            display: flex; align-items: center; gap: 18px;
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.gold   { background: rgba(240,165,0,.15);  color: var(--accent); }
        .stat-icon.blue   { background: rgba(59,130,246,.12); color: #3b82f6; }
        .stat-icon.green  { background: rgba(34,197,94,.12);  color: #22c55e; }
        .stat-icon.red    { background: rgba(239,68,68,.12);  color: #ef4444; }
        .stat-icon.purple { background: rgba(139,92,246,.12); color: #8b5cf6; }

        .stat-value { font-size: 26px; font-weight: 700; line-height: 1.1; }
        .stat-label { font-size: 13px; color: #6b7280; margin-top: 3px; }
        .stat-delta { font-size: 12px; margin-top: 4px; }
        .stat-delta.up   { color: var(--success); }
        .stat-delta.down { color: var(--danger); }

        /* ── Tables ── */
        .table { font-size: 14px; }
        .table thead th {
            background: #f8fafc; font-weight: 600;
            font-size: 12px; letter-spacing: .5px;
            text-transform: uppercase; color: #6b7280;
            border-bottom: 1px solid var(--border);
            padding: 12px 14px;
        }
        .table tbody td { padding: 12px 14px; vertical-align: middle; }
        .table tbody tr:hover { background: #fafbfc; }

        /* ── Badges ── */
        .badge-paid     { background: rgba(34,197,94,.12); color: #16a34a; }
        .badge-partial  { background: rgba(240,165,0,.15);  color: #b45309; }
        .badge-unpaid   { background: rgba(239,68,68,.12);  color: #dc2626; }
        .badge-income   { background: rgba(34,197,94,.12); color: #16a34a; }
        .badge-expense  { background: rgba(239,68,68,.12);  color: #dc2626; }

        .badge { font-weight: 500; font-size: 12px; padding: 4px 10px; border-radius: 20px; }

        /* ── Buttons ── */
        .btn-accent {
            background: var(--accent); border: none; color: #fff;
            font-weight: 600; border-radius: 8px;
        }
        .btn-accent:hover { background: #d99300; color: #fff; }
        .btn-navy  {
            background: var(--navy); border: none; color: #fff;
            font-weight: 600; border-radius: 8px;
        }
        .btn-navy:hover { background: var(--navy-light); color: #fff; }

        /* ── Forms ── */
        .form-control, .form-select {
            border-radius: 8px; border: 1px solid var(--border);
            font-size: 14px; padding: 9px 13px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px rgba(240,165,0,.15);
        }
        .form-label { font-size: 13px; font-weight: 500; color: #374151; }

        /* ── Alerts ── */
        .alert { border-radius: 10px; font-size: 14px; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar, #main-content { left: 0; margin-left: 0; }
        }

        /* ── Misc ── */
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent-soft); color: var(--accent);
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
        }
        .page-header {
            margin-bottom: 24px;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .page-header h1 { font-size: 24px; font-weight: 700; margin: 0; }
        .page-header p { font-size: 14px; color: #6b7280; margin: 0; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center">
            <span class="brand-icon">🏫</span>
            <div>
                <span class="brand-name">SchoolPay</span>
                <span class="brand-sub">Finance Management</span>
            </div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section-label mt-2">Academics</div>
        <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Students
        </a>
        <a href="{{ route('fee-structures.index') }}" class="sidebar-link {{ request()->routeIs('fee-structures.*') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i> Fee Structures
        </a>
        <a href="{{ route('fee-payments.index') }}" class="sidebar-link {{ request()->routeIs('fee-payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Fee Payments
        </a>

        <div class="nav-section-label mt-2">Finance</div>
        <a href="{{ route('transactions.index') }}" class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
        <a href="{{ route('transactions.income') }}" class="sidebar-link {{ request()->routeIs('transactions.income') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-circle"></i> Income
        </a>
        <a href="{{ route('transactions.expenses') }}" class="sidebar-link {{ request()->routeIs('transactions.expenses') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-circle"></i> Expenses
        </a>

        <div class="nav-section-label mt-2">Reports</div>
        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
            <div>
                <div style="font-size:13px;color:#cdd9e5;font-weight:500;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size:11px;">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </div>
</nav>

{{-- ── Topbar ── --}}
<header id="topbar">
    <button class="btn btn-sm d-md-none me-2" id="sidebarToggle"><i class="bi bi-list fs-5"></i></button>
    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>

    <span class="topbar-badge d-none d-sm-inline-flex">
        <i class="bi bi-calendar3" style="font-size:13px;"></i>
        {{ now()->format('D, d M Y') }}
    </span>

    <a href="{{ route('fee-payments.create') }}" class="btn btn-accent btn-sm d-none d-sm-inline-flex gap-1">
        <i class="bi bi-plus-lg"></i> Record Payment
    </a>

    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-sm" style="color:#6b7280;" title="Logout">
            <i class="bi bi-box-arrow-right fs-5"></i>
        </button>
    </form>
</header>

{{-- ── Main ── --}}
<main id="main-content">
    <div class="page-inner">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
@stack('scripts')
</body>
</html>