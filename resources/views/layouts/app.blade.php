<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MedCase') - Medical Case Management</title>
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --surface: #ffffff;
            --danger: #e11d48;
            --danger-light: #ffe4e6;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --success: #059669;
            --success-light: #d1fae5;
            --info: #2563eb;
            --info-light: #dbeafe;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--slate-50);
            color: var(--slate-800);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header & Navbar */
        .header {
            background-color: var(--slate-900);
            color: white;
            border-bottom: 1px solid var(--slate-800);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .brand-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--primary), #0284c7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            list-style: none;
        }

        .nav-item a {
            color: var(--slate-300);
            text-decoration: none;
            font-size: 0.925rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: var(--radius-sm);
            transition: all 0.15s ease;
        }

        .nav-item a:hover,
        .nav-item a.active {
            color: white;
            background-color: var(--slate-800);
        }

        .user-nav-box {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--slate-200);
            background: var(--slate-800);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid var(--slate-700);
        }

        .role-badge {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            letter-spacing: 0.04em;
        }

        .role-badge.role-admin {
            background-color: var(--primary);
            color: white;
        }

        .role-badge.role-viewer {
            background-color: var(--slate-600);
            color: white;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--slate-700);
            color: var(--slate-300);
            font-size: 0.825rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-logout:hover {
            background-color: var(--slate-800);
            color: white;
            border-color: var(--slate-600);
        }

        /* Main Content Container */
        .main-container {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.25rem;
            flex: 1;
        }

        /* Flash Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background-color: var(--success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background-color: var(--danger-light);
            color: #9f1239;
            border: 1px solid #fecdd3;
        }

        .alert-info {
            background-color: var(--info-light);
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-secondary {
            background-color: white;
            color: var(--slate-700);
            border-color: var(--slate-300);
        }

        .btn-secondary:hover {
            background-color: var(--slate-100);
            border-color: var(--slate-400);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #be123c;
        }

        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.8rem;
        }

        /* Cards */
        .card {
            background-color: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--slate-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid var(--slate-200);
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--slate-900);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Status & Priority Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.775rem;
            font-weight: 600;
            line-height: 1;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-draft { background-color: #f1f5f9; color: #475569; }
        .status-draft .status-dot { background-color: #64748b; }

        .status-planning { background-color: var(--warning-light); color: #92400e; }
        .status-planning .status-dot { background-color: var(--warning); }

        .status-in-review { background-color: var(--info-light); color: #1e40af; }
        .status-in-review .status-dot { background-color: var(--info); }

        .status-approved { background-color: #e0e7ff; color: #3730a3; }
        .status-approved .status-dot { background-color: #6366f1; }

        .status-completed { background-color: var(--success-light); color: #065f46; }
        .status-completed .status-dot { background-color: var(--success); }

        .status-cancelled { background-color: var(--danger-light); color: #9f1239; }
        .status-cancelled .status-dot { background-color: var(--danger); }

        .priority-badge {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .priority-high { background-color: #fee2e2; color: #991b1b; }
        .priority-medium { background-color: #fef3c7; color: #92400e; }
        .priority-low { background-color: #f1f5f9; color: #475569; }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        .data-table th {
            background-color: var(--slate-100);
            color: var(--slate-700);
            font-weight: 600;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-200);
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid var(--slate-200);
            color: var(--slate-800);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f8fafc;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.4rem;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.85rem;
            font-family: inherit;
            font-size: 0.925rem;
            border: 1px solid var(--slate-300);
            border-radius: var(--radius-sm);
            background-color: white;
            color: var(--slate-800);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-color: #fffbfa;
        }

        .field-error {
            color: var(--danger);
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.35rem;
            display: block;
        }

        .field-help {
            font-size: 0.775rem;
            color: var(--slate-500);
            margin-top: 0.3rem;
        }

        /* Footer */
        .footer {
            background-color: white;
            border-top: 1px solid var(--slate-200);
            padding: 1.25rem 0;
            text-align: center;
            font-size: 0.825rem;
            color: var(--slate-500);
            margin-top: auto;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .nav-container {
                height: auto;
                padding: 0.75rem 1rem;
                flex-direction: column;
                gap: 0.75rem;
            }

            .nav-menu {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .user-nav-box {
                width: 100%;
                justify-content: space-between;
            }

            .main-container {
                margin: 1.25rem auto;
                padding: 0 0.75rem;
            }
        }
    </style>
</head>
<body>

    @auth
    <header class="header">
        <div class="nav-container">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <div class="brand-icon">+</div>
                <span>MedCase</span>
            </a>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cases.index') }}" class="{{ request()->routeIs('cases.index*') && !request()->routeIs('cases.create') ? 'active' : '' }}">All Cases</a>
                </li>
                @can('create', App\Models\MedicalCase::class)
                <li class="nav-item">
                    <a href="{{ route('cases.create') }}" class="{{ request()->routeIs('cases.create') ? 'active' : '' }}">+ New Case</a>
                </li>
                @endcan
            </ul>

            <div class="user-nav-box">
                <div class="user-pill">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="role-badge {{ auth()->user()->isAdmin() ? 'role-admin' : 'role-viewer' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </header>
    @endauth

    <main class="main-container">
        @if (session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} <strong>MedCase</strong> &mdash; Implant Lifecycle Management &bull; Built for @Jajal Medical</p>
    </footer>

</body>
</html>
