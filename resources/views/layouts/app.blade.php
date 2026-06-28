<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') — TaskFlow</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;
            --primary-light: #EFF6FF;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --slate-900: #0F172A;
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--slate-50);
            color: var(--slate-800);
            min-height: 100vh;
        }

        /* ── Sidebar ─────────────────────────── */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #fff;
            border-right: 1px solid var(--slate-200);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 24px;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar-brand:hover { color: var(--primary); }
        .sidebar-brand i { font-size: 24px; }

        .sidebar-nav {
            flex: 1;
            padding: 8px;
        }

        .sidebar-nav .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            margin: 2px 0;
            border-radius: 8px;
            color: var(--slate-600);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
        }
        .sidebar-nav .nav-item:hover {
            background: var(--slate-100);
            color: var(--slate-800);
        }
        .sidebar-nav .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }
        .sidebar-nav .nav-item i { font-size: 18px; width: 22px; text-align: center; }

        .sidebar-divider {
            border-top: 1px solid var(--slate-200);
            margin: 8px 16px;
        }

        .sidebar-user {
            padding: 16px 24px;
            border-top: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-user .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        .sidebar-user .info { font-size: 13px; }
        .sidebar-user .info .name { font-weight: 600; color: var(--slate-800); }
        .sidebar-user .info .email { color: var(--slate-400); font-size: 11px; }

        /* ── Main Content ────────────────────── */
        .main-content {
            margin-left: 240px;
            padding: 32px;
            min-height: 100vh;
        }

        /* ── Card ───────────────────────────── */
        .card-clean {
            background: #fff;
            border: 1px solid var(--slate-200);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        /* ── Buttons ────────────────────────── */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.15s;
        }
        .btn-primary-custom:hover {
            background: #1D4ED8;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-outline-custom {
            background: #fff;
            border: 1px solid var(--slate-200);
            color: var(--slate-600);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        .btn-outline-custom:hover {
            background: var(--slate-50);
            border-color: var(--slate-300);
        }
        .btn-sm-custom {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* ── Form Controls ──────────────────── */
        .form-control-custom {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* ── Badges ─────────────────────────── */
        .badge-status {
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 100px;
        }
        .badge-belum { background: #F1F5F9; color: #64748B; }
        .badge-proses { background: #FEF3C7; color: #D97706; }
        .badge-review { background: #DBEAFE; color: #2563EB; }
        .badge-selesai { background: #D1FAE5; color: #059669; }

        /* ── Progress Bar ───────────────────── */
        .progress-custom {
            height: 8px;
            border-radius: 100px;
            background: var(--slate-200);
        }
        .progress-custom .progress-bar {
            border-radius: 100px;
        }

        /* ── Bottom Nav (Mobile) ────────────── */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid var(--slate-200);
            z-index: 200;
            padding: 8px 0;
            justify-content: space-around;
        }
        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            color: var(--slate-400);
            text-decoration: none;
            font-size: 10px;
            padding: 4px 12px;
        }
        .bottom-nav a.active { color: var(--primary); }
        .bottom-nav a i { font-size: 20px; }

        /* ── Page Heading ───────────────────── */
        .page-heading {
            font-size: 24px;
            font-weight: 700;
            color: var(--slate-900);
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--slate-500);
        }

        /* ── Table Modern ──────────────────── */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-modern thead th {
            background: var(--slate-50);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: var(--slate-500);
            padding: 12px 16px;
            border-bottom: 1px solid var(--slate-200);
        }
        .table-modern tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--slate-100);
            font-size: 14px;
            vertical-align: middle;
        }
        .table-modern tbody tr:hover { background: var(--slate-50); }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 16px; padding-bottom: 80px; }
            .bottom-nav { display: flex; }
        }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="bi-kanban"></i> TaskFlow
        </a>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <i class="bi-folder2"></i> Proyek Saya
            </a>
            <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="bi-check2-square"></i> Tugas Saya
            </a>
            <a href="{{ route('courses.index') }}" class="nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                <i class="bi-journal-bookmark-fill"></i> Mata Kuliah
            </a>
            <a href="{{ route('lecturers.index') }}" class="nav-item {{ request()->routeIs('lecturers.*') ? 'active' : '' }}">
                <i class="bi-person-workspace"></i> Dosen
            </a>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi-people"></i> Pengguna
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi-graph-up"></i> Laporan
            </a>
            <a href="{{ route('activities.index') }}" class="nav-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                <i class="bi-clock-history"></i> Riwayat
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi-gear"></i> Pengaturan
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="nav-item w-100 border-0 bg-transparent text-start" style="cursor:pointer;">
                    <i class="bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </nav>

        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success border-0" style="background:#D1FAE5;color:#059669;border-radius:10px;font-size:14px;">
                <i class="bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    {{-- BOTTOM NAV (MOBILE) --}}
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}"><i class="bi-folder2"></i> Proyek</a>
        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}"><i class="bi-check2-square"></i> Tugas</a>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="bi-graph-up"></i> Laporan</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="bi-person"></i> Profil</a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
