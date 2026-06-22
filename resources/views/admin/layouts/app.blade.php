<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; color: #1f2937; }
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; background: #1f2937; color: #fff; padding: 1.5rem 0; overflow-y: auto; }
        .sidebar h2 { padding: 0 1.5rem; margin-bottom: 2rem; font-size: 1.25rem; }
        .sidebar a { display: block; padding: 0.75rem 1.5rem; color: #d1d5db; text-decoration: none; font-size: 0.9rem; }
        .sidebar a:hover, .sidebar a.active { background: #374151; color: #fff; }
        .main { margin-left: 240px; padding: 2rem; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .topbar h1 { font-size: 1.5rem; }
        .card { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .grid { display: grid; gap: 1.5rem; }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        .stat-card { text-align: center; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #2563eb; }
        .stat-card .label { color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: #6b7280; }
        .btn { display: inline-block; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.875rem; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.35rem; font-weight: 500; font-size: 0.875rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .pagination { display: flex; gap: 0.25rem; margin-top: 1rem; }
        .pagination a, .pagination span { padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; text-decoration: none; font-size: 0.85rem; color: #374151; }
        .pagination span.current { background: #2563eb; color: #fff; border-color: #2563eb; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .logout-form { display: inline; }
        .logout-btn { background: none; border: none; color: #d1d5db; cursor: pointer; font-size: 0.9rem; padding: 0.75rem 1.5rem; width: 100%; text-align: left; }
        .logout-btn:hover { background: #374151; color: #fff; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    <div class="main">
        <div class="topbar">
            <h1>@yield('title', 'Dashboard')</h1>
            <div>{{ Auth::user()->name }}</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
