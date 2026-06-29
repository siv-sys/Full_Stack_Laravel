<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #f1f5f9;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --success: #10b981;
            --warning: #f59e0b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --radius: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 0.5rem;
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-icon svg { width: 20px; height: 20px; color: #fff; }

        .sidebar-brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: -0.025em;
        }

        .sidebar-section {
            padding: 0.75rem 1rem;
            font-size: 0.675rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gray-500);
        }

        .sidebar-nav { flex: 1; padding: 0 0.75rem; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius);
            margin-bottom: 2px;
            transition: all 0.15s ease;
            position: relative;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }

        .sidebar-link.active {
            background: var(--primary);
            color: #fff;
        }

        .sidebar-link.active svg { color: #fff; }

        .sidebar-link svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: var(--gray-500);
            transition: color 0.15s ease;
        }

        .sidebar-link:hover svg { color: var(--sidebar-text-active); }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: auto;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.5rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .sidebar-user-info { overflow: hidden; }
        .sidebar-user-name { font-size: 0.875rem; font-weight: 600; color: var(--sidebar-text-active); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 0.75rem; color: var(--gray-500); }

        .logout-form { display: block; }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.625rem 0.75rem;
            background: none;
            border: none;
            color: var(--sidebar-text);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius);
            transition: all 0.15s ease;
            text-align: left;
            font-family: inherit;
        }
        .logout-btn:hover { background: rgba(239, 68, 68, 0.1); color: #fca5a5; }
        .logout-btn svg { width: 20px; height: 20px; flex-shrink: 0; }

        /* ─── Mobile toggle ─── */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 200;
            width: 40px;
            height: 40px;
            background: var(--sidebar-bg);
            border: none;
            border-radius: var(--radius);
            color: #fff;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }
        .mobile-toggle svg { width: 20px; height: 20px; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 90;
        }

        /* ─── Main area ─── */
        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }

        .topbar h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.025em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-date {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .content-area { padding: 1.5rem 2rem 2rem; }

        /* ─── Page header ─── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-left { display: flex; flex-direction: column; gap: 0.25rem; }
        .page-subtitle { font-size: 0.875rem; color: var(--gray-500); }

        /* ─── Cards ─── */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        /* ─── Stat cards ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg { width: 24px; height: 24px; }

        .stat-icon-blue { background: #eff6ff; color: #3b82f6; }
        .stat-icon-green { background: #ecfdf5; color: #10b981; }
        .stat-icon-purple { background: #f5f3ff; color: #8b5cf6; }
        .stat-icon-amber { background: #fffbeb; color: #f59e0b; }

        .stat-info { flex: 1; min-width: 0; }
        .stat-label { font-size: 0.8rem; color: var(--gray-500); font-weight: 500; margin-bottom: 0.25rem; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); letter-spacing: -0.025em; line-height: 1.2; }

        /* ─── Grid ─── */
        .grid { display: grid; gap: 1.5rem; }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }

        /* ─── Tables ─── */
        table { width: 100%; border-collapse: collapse; }

        th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }

        tbody tr { transition: background 0.1s ease; }
        tbody tr:hover { background: var(--gray-50); }

        .table-empty {
            text-align: center;
            color: var(--gray-400);
            padding: 3rem 1rem !important;
        }

        .table-empty-icon { margin-bottom: 0.75rem; color: var(--gray-300); }
        .table-empty-icon svg { width: 48px; height: 48px; }
        .table-empty-text { font-size: 0.875rem; font-weight: 500; }
        .table-empty-sub { font-size: 0.8rem; color: var(--gray-400); margin-top: 0.25rem; }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: inherit;
            line-height: 1.5;
            white-space: nowrap;
        }
        .btn svg { width: 16px; height: 16px; }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); box-shadow: 0 1px 3px rgba(79, 70, 229, 0.3); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: var(--danger-hover); }

        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }

        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #d97706; }

        .btn-ghost { background: transparent; color: var(--gray-600); border: 1px solid var(--gray-300); }
        .btn-ghost:hover { background: var(--gray-50); border-color: var(--gray-400); }

        .btn-light { background: var(--gray-100); color: var(--gray-700); }
        .btn-light:hover { background: var(--gray-200); }

        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; border-radius: 6px; }
        .btn-sm svg { width: 14px; height: 14px; }

        .btn-icon { padding: 0.5rem; }

        .btn-group { display: flex; gap: 0.375rem; align-items: center; flex-wrap: wrap; }

        /* ─── Forms ─── */
        .form-group { margin-bottom: 1.25rem; }

        .form-group label {
            display: block;
            margin-bottom: 0.375rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--gray-700);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--gray-800);
            background: #fff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group input::placeholder { color: var(--gray-400); }
        .form-group textarea { resize: vertical; min-height: 120px; }

        .form-hint { font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem; }
        .form-error { color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500; }
        .form-group input.is-error,
        .form-group select.is-error { border-color: var(--danger); }
        .form-group input.is-error:focus,
        .form-group select.is-error:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }

        /* ─── Alerts ─── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px; }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-pending { background: #fffbeb; color: #92400e; }
        .badge-pending .badge-dot { background: #f59e0b; }

        .badge-completed { background: #ecfdf5; color: #065f46; }
        .badge-completed .badge-dot { background: #10b981; }

        .badge-processing { background: #eff6ff; color: #1e40af; }
        .badge-processing .badge-dot { background: #3b82f6; }

        .badge-cancelled { background: #fef2f2; color: #991b1b; }
        .badge-cancelled .badge-dot { background: #ef4444; }

        .badge-admin { background: #eff6ff; color: #1e40af; }
        .badge-customer { background: var(--gray-100); color: var(--gray-600); }

        /* ─── Pagination ─── */
        .pagination {
            display: flex;
            gap: 0.25rem;
            margin-top: 1.25rem;
            justify-content: center;
        }

        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-600);
            transition: all 0.15s ease;
        }

        .pagination a:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .pagination span.current {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .pagination span.disabled {
            color: var(--gray-300);
            cursor: not-allowed;
        }

        /* ─── Image thumb ─── */
        .img-thumb {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
        }

        .img-placeholder {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
        }

        .img-placeholder svg { width: 20px; height: 20px; }

        /* ─── Filter bar ─── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.8rem;
            font-family: inherit;
            color: var(--gray-700);
            background: #fff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* ─── Tab pills ─── */
        .tab-pills {
            display: flex;
            gap: 0.375rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .tab-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.4rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
            background: #fff;
            transition: all 0.15s ease;
        }

        .tab-pill:hover { background: var(--gray-50); border-color: var(--gray-300); }

        .tab-pill.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .tab-pill-count {
            background: rgba(0,0,0,0.08);
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            font-size: 0.7rem;
        }

        .tab-pill.active .tab-pill-count { background: rgba(255,255,255,0.2); }

        /* ─── Info grid (order/user details) ─── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item { }
        .info-label { font-size: 0.75rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
        .info-value { font-size: 0.9rem; color: var(--gray-800); font-weight: 500; }

        /* ─── Status update box ─── */
        .status-box {
            padding: 1rem 1.25rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .status-box label { font-weight: 600; font-size: 0.8rem; color: var(--gray-700); white-space: nowrap; }

        .status-box select {
            padding: 0.4rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.8rem;
            font-family: inherit;
            background: #fff;
        }

        /* ─── Back link ─── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-500);
            text-decoration: none;
            transition: color 0.15s ease;
            margin-bottom: 1rem;
        }
        .back-link:hover { color: var(--primary); }
        .back-link svg { width: 16px; height: 16px; }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .mobile-toggle { display: flex; }
            .main { margin-left: 0; }
            .topbar { padding: 0 1rem 0 3.5rem; }
            .content-area { padding: 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    {{-- Mobile toggle --}}
    <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open');document.querySelector('.sidebar-overlay').classList.toggle('open');">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    </button>
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open');this.classList.remove('open');"></div>

    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/></svg>
            </div>
            <span class="sidebar-brand-text">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                Dashboard
            </a>

            <div class="sidebar-section">Catalog</div>

            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                Categories
            </a>

            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/></svg>
                Products
            </a>

            <div class="sidebar-section">Sales</div>

            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                Orders
            </a>

            <div class="sidebar-section">People</div>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                Users
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    {{-- Main content --}}
    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>@yield('title', 'Dashboard')</h1>
            </div>
            <div class="topbar-right">
                <span class="topbar-date">{{ now()->format('D, M d Y') }}</span>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
