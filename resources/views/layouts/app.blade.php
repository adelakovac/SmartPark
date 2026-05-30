<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .navbar {
            background: linear-gradient(90deg, #0f172a, #1e293b);
            color: white;
            padding: 18px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
            margin-top: 10px;
        }

        .label {
            font-size: 14px;
            color: #6b7280;
        }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-disabled {
            background: #9ca3af;
            color: white;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .spot-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .spot-card {
            background: white;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            border-left: 6px solid #d1d5db;
        }

        .spot-card.available {
            border-left-color: #16a34a;
        }

        .spot-card.occupied {
            border-left-color: #dc2626;
        }

        .spot-card.reserved {
            border-left-color: #f59e0b;
        }

        .spot-number {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
        }

        .badge.available {
            background: #dcfce7;
            color: #166534;
        }

        .badge.occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge.reserved {
            background: #fef3c7;
            color: #92400e;
        }

        .meta {
            color: #6b7280;
            font-size: 14px;
            margin-top: 8px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 650px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .empty-state {
            background: white;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            color: #6b7280;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: end;
            margin-bottom: 24px;
        }

        .filter-box {
            background: white;
            padding: 18px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            margin-bottom: 24px;
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 24px;
        }

        .page-link {
            padding: 10px 14px;
            border-radius: 10px;
            background: white;
            text-decoration: none;
            color: #1f2937;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            font-weight: 600;
        }

        .page-link.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .small-input {
            min-width: 220px;
        }
        .stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #1e293b;
}
.btn-logout {
    background: #ef4444;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-logout:hover {
    background: #dc2626;
}
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/locations" class="brand"> SmartPark</a>

        <div class="nav-links">
    <a href="/locations">Locations</a>
    <a href="/reservations">Reservations</a>

    @auth
        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-weight:600; color:white;">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout
                </button>
            </form>
        </div>
    @endauth

    @guest
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    @endguest
</div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>