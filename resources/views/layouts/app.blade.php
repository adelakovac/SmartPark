<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark — @yield('title', 'Dashboard')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0f172a;
            --navy2:  #1e293b;
            --blue:   #2563eb;
            --blue2:  #1d4ed8;
            --green:  #16a34a;
            --red:    #dc2626;
            --amber:  #f59e0b;
            --slate:  #64748b;
            --light:  #f8fafc;
            --border: #e2e8f0;
            --radius: 12px;
            --shadow: 0 4px 20px rgba(15,23,42,0.08);
        }

        body {
            font-family: 'Segoe UI', system-ui, Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--navy);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: 0 2px 12px rgba(0,0,0,0.25);
        }
        .brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; color: white;
            font-size: 20px; font-weight: 700;
        }
        .brand-icon {
            width: 32px; height: 32px; background: var(--blue);
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; font-size: 16px;
        }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a {
            color: #94a3b8; text-decoration: none; font-size: 13px;
            font-weight: 500; padding: 6px 12px; border-radius: 8px;
            transition: all 0.15s;
        }
        .nav-links a:hover { color: white; background: rgba(255,255,255,0.08); }
        .nav-links a.active { color: white; background: rgba(37,99,235,0.3); }
        .nav-links .admin-link {
            color: #60a5fa; border: 1px solid rgba(96,165,250,0.3); margin-left: 4px;
        }
        .nav-right { display: flex; align-items: center; gap: 10px; }
        .nav-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--blue); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; flex-shrink: 0;
        }
        .nav-user-link {
            display: flex; align-items: center; gap: 8px;
            text-decoration: none; padding: 4px 8px; border-radius: 8px;
            transition: background 0.15s;
        }
        .nav-user-link:hover { background: rgba(255,255,255,0.07); }
        .nav-name { font-size: 13px; color: #cbd5e1; font-weight: 500; line-height: 1.2; }
        .nav-role { font-size: 10px; font-weight: 600; }
        .btn-logout {
            background: transparent; border: 1px solid #334155;
            color: #94a3b8; padding: 6px 14px; border-radius: 8px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s;
        }
        .btn-logout:hover { border-color: var(--red); color: var(--red); }

        /* ── HAMBURGER ── */
        .hamburger {
            display: none;
            background: transparent;
            border: 1px solid #334155;
            color: white;
            font-size: 20px;
            padding: 4px 10px;
            border-radius: 8px;
            cursor: pointer;
            line-height: 1;
        }

        /* ── MOBILE MENU ── */
        .mobile-menu {
            display: none;
            flex-direction: column;
            background: #1e293b;
            position: sticky;
            top: 60px;
            z-index: 499;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: #94a3b8; text-decoration: none;
            padding: 16px 20px; font-size: 15px; font-weight: 500;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
            display: flex; align-items: center; gap: 10px;
        }
        .mobile-menu a:hover { background: rgba(255,255,255,0.05); color: white; }
        .mobile-menu a.admin-m { color: #60a5fa; }
        .mobile-menu-logout {
            width: 100%; text-align: left; background: none; border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
            color: #f87171; font-size: 15px; padding: 16px 20px;
            cursor: pointer; font-weight: 600; font-family: inherit;
            display: flex; align-items: center; gap: 10px;
        }
        .mobile-menu-logout:hover { background: rgba(220,38,38,0.08); }

        /* ── CONTAINER ── */
        .container { max-width: 1200px; margin: 28px auto; padding: 0 20px; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex; justify-content: space-between;
            align-items: flex-start; gap: 14px;
            flex-wrap: wrap; margin-bottom: 24px;
        }
        .page-title { font-size: 26px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .page-subtitle { font-size: 14px; color: var(--slate); margin-top: 4px; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px; border-radius: 10px;
            text-decoration: none; font-weight: 600;
            border: none; cursor: pointer; font-size: 13px;
            transition: all 0.15s; white-space: nowrap;
        }
        .btn-primary   { background: var(--blue);  color: white; }
        .btn-primary:hover  { background: var(--blue2); }
        .btn-success   { background: var(--green); color: white; }
        .btn-success:hover  { background: #15803d; }
        .btn-danger    { background: var(--red);   color: white; }
        .btn-danger:hover   { background: #b91c1c; }
        .btn-secondary { background: white; color: #374151; border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--light); }
        .btn-disabled  { background: #cbd5e1; color: white; cursor: not-allowed; }
        .btn-amber     { background: var(--amber); color: white; }
        .btn-amber:hover { background: #d97706; }
        .btn-sm { padding: 7px 14px; font-size: 12px; }

        .actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px; margin-bottom: 24px;
        }
        .stat-card {
            background: white; border-radius: var(--radius);
            padding: 18px; box-shadow: var(--shadow);
            border-top: 3px solid transparent;
        }
        .stat-card.blue   { border-top-color: var(--blue); }
        .stat-card.green  { border-top-color: var(--green); }
        .stat-card.red    { border-top-color: var(--red); }
        .stat-card.amber  { border-top-color: var(--amber); }
        .stat-card.navy   { border-top-color: var(--navy2); }
        .stat-label { font-size: 11px; color: var(--slate); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-number { font-size: 28px; font-weight: 700; color: #0f172a; margin-top: 6px; line-height: 1; }
        .stat-number.green { color: var(--green); }
        .stat-number.red   { color: var(--red); }
        .stat-number.amber { color: var(--amber); }
        .stat-number.blue  { color: var(--blue); }

        /* ── CARD ── */
        .card { background: white; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
        .card-title { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 14px; }

        /* ── GRID ── */
        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px; margin-bottom: 20px;
        }

        /* ── LOC CARD ── */
        .loc-card {
            background: white; border-radius: var(--radius);
            box-shadow: var(--shadow); overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s;
            display: flex; flex-direction: column;
        }
        .loc-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(15,23,42,0.12); }
        .loc-card-header { background: linear-gradient(135deg, var(--navy), var(--navy2)); padding: 18px; color: white; }
        .loc-card-name { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
        .loc-card-addr { font-size: 12px; color: #94a3b8; }
        .loc-card-body { padding: 16px; flex: 1; }
        .loc-card-footer { padding: 12px 16px; border-top: 1px solid var(--border); display: flex; gap: 8px; }

        /* ── AVAILABILITY BAR ── */
        .avail-bar { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; margin: 10px 0 4px; }
        .avail-fill { height: 100%; border-radius: 3px; transition: width 0.4s; }
        .avail-label { font-size: 11px; color: var(--slate); }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
        }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-amber  { background: #fef3c7; color: #92400e; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-gray   { background: #f1f5f9; color: #475569; }

        /* ── SPOT GRID ── */
        .spot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 12px; margin-bottom: 20px;
        }
        .spot-card {
            background: white; border-radius: var(--radius);
            padding: 14px; box-shadow: var(--shadow);
            border-left: 5px solid #e2e8f0;
        }
        .spot-card.available { border-left-color: var(--green); }
        .spot-card.occupied  { border-left-color: var(--red); }
        .spot-card.reserved  { border-left-color: var(--amber); }
        .spot-number-text { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .spot-type { font-size: 11px; color: var(--slate); margin-bottom: 6px; }
        .spot-actions { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 8px; }

        /* ── FORM ── */
        .form-wrap { max-width: 680px; }
        .form-card { background: white; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: 1 / -1; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        input[type=text], input[type=number], input[type=email],
        input[type=password], select, textarea {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 14px; color: #0f172a; background: white;
            transition: border-color 0.15s; outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        textarea { min-height: 100px; resize: vertical; }
        .form-hint { font-size: 11px; color: var(--slate); margin-top: 4px; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap; }

        /* ── FILTER ── */
        .filter-box { background: white; border-radius: var(--radius); padding: 16px 18px; box-shadow: var(--shadow); margin-bottom: 20px; }
        .filter-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { margin-bottom: 0; }
        .filter-group input, .filter-group select { min-width: 150px; }

        /* ── PAGINATION ── */
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 24px; }
        .page-btn {
            padding: 8px 16px; border-radius: 8px; background: white;
            text-decoration: none; color: #374151; font-size: 13px;
            font-weight: 600; box-shadow: var(--shadow); border: 1px solid var(--border);
            transition: all 0.15s;
        }
        .page-btn:hover { background: var(--blue); color: white; border-color: var(--blue); }
        .page-btn.disabled { opacity: 0.4; pointer-events: none; }
        .page-btn.current { background: var(--blue); color: white; border-color: var(--blue); }

        /* ── ALERTS ── */
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; border-left: 4px solid var(--green); }
        .alert-error   { background: #fee2e2; color: #991b1b; border-left: 4px solid var(--red); }
        .alert-warn    { background: #fef3c7; color: #92400e; border-left: 4px solid var(--amber); }
        .alert ul { margin: 4px 0 0 16px; }

        /* ── EMPTY STATE ── */
        .empty-state { background: white; border-radius: var(--radius); padding: 40px 20px; text-align: center; box-shadow: var(--shadow); }
        .empty-icon { font-size: 44px; margin-bottom: 12px; }
        .empty-title { font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 8px; }
        .empty-text { font-size: 14px; color: var(--slate); margin-bottom: 20px; }

        /* ── SECTION TITLE ── */
        .section-title { font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 500px; }
        thead th {
            text-align: left; padding: 10px 14px;
            font-size: 12px; font-weight: 700; color: var(--slate);
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border); background: var(--light);
        }
        tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.1s; }
        tbody tr:hover { background: #f8fafc; }
        tbody td { padding: 12px 14px; vertical-align: middle; }

        .meta { font-size: 13px; color: var(--slate); }
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

        /* ══════════════════════════════════════════
           MOBILE
        ══════════════════════════════════════════ */
        @media (max-width: 768px) {
            .navbar { padding: 0 16px; height: 56px; }
            .nav-links { display: none !important; }
            .nav-name, .nav-role { display: none; }
            .btn-logout { display: none; }
            .hamburger { display: block; }

            .container { margin: 16px auto; padding: 0 14px; }

            .page-title { font-size: 22px; }
            .page-header { flex-direction: column; gap: 12px; margin-bottom: 18px; }
            .page-header .actions { width: 100%; }
            .page-header .actions .btn { flex: 1; justify-content: center; }

            .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
            .stat-card { padding: 14px; }
            .stat-number { font-size: 24px; }

            .grid-cards { grid-template-columns: 1fr; gap: 14px; }

            .spot-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .spot-number-text { font-size: 18px; }

            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 18px; }
            .form-wrap { max-width: 100%; }

            .filter-box { padding: 14px; }
            .filter-row { flex-direction: column; gap: 8px; }
            .filter-group input, .filter-group select { min-width: 100%; }
            .filter-row .actions { width: 100%; }
            .filter-row .actions .btn { flex: 1; justify-content: center; }

            .btn { padding: 10px 14px; font-size: 13px; }
            .btn-sm { padding: 7px 12px; font-size: 12px; }

            .dash-grid { display: block !important; }
            .dash-grid > * { margin-bottom: 16px; }

            .spot-actions { gap: 4px; }
            .spot-actions .btn-sm { font-size: 11px; padding: 5px 8px; }

            .pagination { gap: 6px; }
            .page-btn { padding: 8px 12px; font-size: 12px; }

            .empty-state { padding: 32px 16px; }

            .profile-grid { display: block !important; }
            .profile-grid > * { margin-bottom: 16px; }
        }

        @media (max-width: 400px) {
            .spot-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/map" class="brand">
        <div class="brand-icon">🅿</div>
        SmartPark
    </a>

    <div class="nav-links">
        <a href="/map"          class="{{ request()->is('map')           ? 'active' : '' }}">🗺 Map</a>
        <a href="/dashboard"    class="{{ request()->is('dashboard')     ? 'active' : '' }}">Dashboard</a>
        <a href="/locations"    class="{{ request()->is('locations*')    ? 'active' : '' }}">Locations</a>
        <a href="/reservations" class="{{ request()->is('reservations*') ? 'active' : '' }}">My Reservations</a>
        <a href="/favorites"    class="{{ request()->is('favorites*')    ? 'active' : '' }}">♡ Favourites</a>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="/locations/create"   class="admin-link">+ Add Location</a>
            <a href="/admin/reservations" class="admin-link">🎫 Reservations</a>
            <a href="/admin/users"        class="admin-link">👥 Users</a>
            <a href="/admin/reports"      class="admin-link">🚩 Reports</a>
        @endif
    </div>

    @auth
    <div class="nav-right">
        <a href="{{ route('profile.edit') }}" class="nav-user-link">
            <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="display:flex; flex-direction:column; line-height:1.3;">
                <span class="nav-name">{{ auth()->user()->name }}</span>
                @if(auth()->user()->role === 'admin')
                    <span class="nav-role" style="color:#60a5fa;">Administrator</span>
                @else
                    <span class="nav-role" style="color:#64748b;">My Profile</span>
                @endif
            </div>
        </a>
        <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">☰</button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
    @endauth
</nav>

@auth
<div class="mobile-menu" id="mobileMenu">
    <a href="/map">🗺 Map</a>
    <a href="/dashboard">📊 Dashboard</a>
    <a href="/locations">📍 Locations</a>
    <a href="/reservations">🎫 My Reservations</a>
    <a href="{{ route('profile.edit') }}">👤 My Profile</a>
    <a href="/favorites">♡ Favourites</a>
    @if(auth()->user()->role === 'admin')
        <a href="/locations/create" class="admin-m">+ Add Location</a>
        <a href="/admin/reservations" class="admin-m">🎫 All Reservations</a>
        <a href="/admin/users" class="admin-m">👥 Users</a>
        <a href="/admin/reports" class="admin-m">🚩 Reports</a>
    @endif
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="mobile-menu-logout">🚪 Logout</button>
    </form>
</div>
@endauth

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const btn  = document.getElementById('hamburger');
    if (!menu) return;
    menu.classList.toggle('open');
    btn.textContent = menu.classList.contains('open') ? '✕' : '☰';
}
document.querySelectorAll('.mobile-menu a').forEach(a => {
    a.addEventListener('click', () => {
        const menu = document.getElementById('mobileMenu');
        const btn  = document.getElementById('hamburger');
        if (menu) menu.classList.remove('open');
        if (btn)  btn.textContent = '☰';
    });
});
</script>

</body>
</html>