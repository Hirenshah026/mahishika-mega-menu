<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') · Mahishika Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-primary: #4338ca;
            --admin-sidebar: #111827;
            --admin-bg: #f4f6fb;
        }

        body {
            background: var(--admin-bg);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            padding: 1.5rem 1rem;
            background: linear-gradient(180deg, #1e1b4b, var(--admin-sidebar));
            color: #fff;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 2rem;
            padding: 0 .5rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .sidebar .brand-mark {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #818cf8;
            color: #1e1b4b;
        }

        .sidebar .nav-label {
            padding: 0 .75rem;
            margin-bottom: .5rem;
            font-size: .7rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #9ca3af;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem .75rem;
            margin-bottom: .25rem;
            border-radius: 10px;
            color: #d1d5db;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff;
        }

        .sidebar a.active {
            background: #4f46e5;
            color: #fff;
        }

        /* Logged-in user + logout */
        .user-box {
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
        }

        .user-box small {
            color: #9ca3af;
        }

        .user-avatar {
            display: grid;
            place-items: center;
            flex: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #818cf8;
            color: #1e1b4b;
            font-weight: 700;
        }

        .btn-logout {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 10px;
            background: rgba(239, 68, 68, .15);
            color: #fca5a5;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Main area */
        .main {
            margin-left: 250px;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .page-header p {
            margin: .25rem 0 0;
            color: #6b7280;
        }

        .panel {
            border: 0;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 30px -15px rgba(17, 24, 39, .25);
        }

        .table {
            margin: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 1rem;
            background: #f9fafb;
            font-size: .75rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .table td {
            padding: 1rem;
        }

        .btn-primary {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-primary:hover {
            background: #3730a3;
            border-color: #3730a3;
        }

        .badge-soft-success { background: #dcfce7; color: #166534; }
        .badge-soft-muted { background: #f3f4f6; color: #4b5563; }
        .badge-soft-primary { background: #e0e7ff; color: #3730a3; }
        .badge-soft-teal { background: #ccfbf1; color: #115e59; }

        /* Image upload box */
        .upload-box {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 200px;
            overflow: hidden;
            border: 2px dashed #c7d2fe;
            border-radius: 14px;
            background: #f5f7ff;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .upload-box:hover {
            border-color: var(--admin-primary);
            background: #eef2ff;
        }

        .upload-box.is-invalid {
            border-color: #dc3545;
        }

        #image:focus-visible + .upload-box {
            outline: 3px solid #818cf8;
        }

        .upload-box img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .upload-hint {
            display: grid;
            justify-items: center;
            gap: .25rem;
            padding: 2rem;
            color: #4b5563;
            text-align: center;
        }

        .upload-hint i {
            font-size: 2.5rem;
            color: var(--admin-primary);
        }

        .upload-change {
            position: absolute;
            right: .75rem;
            bottom: .75rem;
            padding: .35rem .8rem;
            border-radius: 999px;
            background: rgba(17, 24, 39, .75);
            color: #fff;
            font-size: .85rem;
        }

        .upload-hint[hidden],
        .upload-change[hidden],
        .upload-box img[hidden] {
            display: none;
        }

        .empty {
            padding: 3rem;
            text-align: center;
            color: #6b7280;
        }

        /* Mobile: sidebar becomes a top bar */
        @media (max-width: 991.98px) {
            .sidebar {
                position: static;
                width: auto;
                padding: 1rem;
            }

            .sidebar .brand {
                margin-bottom: 1rem;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: .25rem;
            }

            .sidebar .nav-label {
                display: none;
            }

            .user-box {
                position: static;
                margin-top: 1rem;
            }

            .main {
                margin-left: 0;
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <span class="brand-mark">M</span>
        Mahishika Admin
    </div>

    <p class="nav-label">Mega menu</p>
    <nav>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Categories
        </a>
        <a href="{{ route('admin.sub-categories.index') }}" class="{{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i> Sub-categories
        </a>
        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-richtext"></i> Portfolio / Articles
        </a>
    </nav>

    <div class="user-box">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="flex-grow-1 text-truncate">
            <strong class="d-block text-truncate">{{ auth()->user()->name }}</strong>
            <small class="text-truncate d-block">{{ auth()->user()->email }}</small>
        </div>
        <form method="POST" action="{{ route('logout') }}" data-confirm="You will be logged out of the admin panel." data-confirm-button="Yes, log out">
            @csrf
            <button class="btn-logout" title="Logout" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</aside>

<main class="main">
    @yield('content')
</main>

@include('admin.partials.sweetalert')
</body>
</html>
