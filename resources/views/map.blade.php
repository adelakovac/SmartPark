<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark — Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, Arial, sans-serif; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }

        .navbar {
            background: #0f172a; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; flex-shrink: 0; z-index: 1000;
            box-shadow: 0 1px 0 rgba(255,255,255,0.06);
        }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; color:white; font-size:19px; font-weight:700; }
        .brand-icon { width:32px; height:32px; background:#2563eb; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; }
        .nav-links { display:flex; align-items:center; gap:4px; }
        .nav-links a { color:#94a3b8; text-decoration:none; font-size:13px; font-weight:500; padding:6px 12px; border-radius:8px; transition:all 0.15s; }
        .nav-links a:hover { color:white; background:rgba(255,255,255,0.08); }
        .nav-links a.active { color:white; background:rgba(37,99,235,0.4); }
        .nav-links .admin-link { color:#60a5fa; border:1px solid rgba(96,165,250,0.25); }
        .nav-right { display:flex; align-items:center; gap:10px; }
        .nav-avatar { width:30px; height:30px; border-radius:50%; background:#2563eb; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; }
        .nav-name { font-size:13px; color:#cbd5e1; }
        .badge-purple { background:#ede9fe; color:#5b21b6; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:700; }
        .btn-logout { background:transparent; border:1px solid #334155; color:#94a3b8; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:all 0.15s; }
        .btn-logout:hover { border-color:#ef4444; color:#ef4444; }

        .app-body { display:flex; flex:1; overflow:hidden; }

        /* SIDEBAR */
        .sidebar { width:340px; flex-shrink:0; background:#1e293b; display:flex; flex-direction:column; overflow:hidden; border-right:1px solid rgba(255,255,255,0.06); }
        .sidebar-header { padding:16px; border-bottom:1px solid rgba(255,255,255,0.06); }
        .sidebar-title { font-size:14px; font-weight:700; color:white; margin-bottom:10px; display:flex; align-items:center; justify-content:space-between; }
        .sidebar-count { font-size:12px; font-weight:400; color:#64748b; }

        .search-wrap { position:relative; margin-bottom:10px; }
        .search-icon { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#64748b; }
        .search-input { width:100%; padding:9px 12px 9px 32px; background:#0f172a; border:1px solid #334155; border-radius:10px; font-size:13px; color:white; outline:none; transition:border-color 0.15s; }
        .search-input::placeholder { color:#475569; }
        .search-input:focus { border-color:#3b82f6; }

        .filter-chips { display:flex; gap:6px; flex-wrap:wrap; }
        .chip { padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; cursor:pointer; border:1px solid #334155; background:transparent; color:#64748b; transition:all 0.15s; }
        .chip:hover { border-color:#3b82f6; color:#3b82f6; }
        .chip.active { background:#3b82f6; color:white; border-color:#3b82f6; }

        .loc-list { flex:1; overflow-y:auto; }
        .loc-list::-webkit-scrollbar { width:4px; }
        .loc-list::-webkit-scrollbar-thumb { background:#334155; border-radius:2px; }

        .loc-item { padding:14px 16px; border-bottom:1px solid rgba(255,255,255,0.04); cursor:pointer; transition:background 0.15s; position:relative; }
        .loc-item:hover { background:rgba(255,255,255,0.04); }
        .loc-item.active { background:rgba(37,99,235,0.15); border-left:3px solid #3b82f6; }
        .loc-item-name { font-size:14px; font-weight:600; color:white; margin-bottom:3px; }
        .loc-item-addr { font-size:11px; color:#64748b; margin-bottom:8px; }
        .loc-item-rate { position:absolute; right:16px; top:14px; font-size:13px; font-weight:700; color:#60a5fa; }
        .loc-pills { display:flex; gap:5px; flex-wrap:wrap; margin-bottom:6px; }
        .pill { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; }
        .pill-green { background:rgba(22,163,74,0.2); color:#4ade80; }
        .pill-red   { background:rgba(220,38,38,0.2); color:#f87171; }
        .pill-amber { background:rgba(245,158,11,0.2); color:#fbbf24; }
        .pill-gray  { background:rgba(100,116,139,0.2); color:#94a3b8; }
        .pill-blue  { background:rgba(37,99,235,0.2); color:#60a5fa; }

        .avail-bar { height:3px; background:#334155; border-radius:2px; overflow:hidden; }
        .avail-fill { height:100%; border-radius:2px; }
        .no-results { text-align:center; padding:40px 20px; color:#475569; font-size:13px; }

        #map { flex:1; }

        /* MARKERS */
        .sp-marker { width:38px; height:38px; border-radius:50% 50% 50% 0; transform:rotate(-45deg); display:flex; align-items:center; justify-content:center; box-shadow:0 3px 10px rgba(0,0,0,0.4); border:2px solid rgba(255,255,255,0.9); }
        .sp-marker-inner { transform:rotate(45deg); font-weight:700; font-size:11px; color:white; }
        .m-green { background:#16a34a; }
        .m-amber { background:#f59e0b; }
        .m-red   { background:#dc2626; }
        .m-gray  { background:#64748b; }

        /* POPUP */
        .sp-popup { font-family:'Segoe UI',system-ui,Arial,sans-serif; min-width:230px; }
        .sp-popup h3 { font-size:15px; font-weight:700; color:#0f172a; margin:0 0 3px; }
        .sp-popup .addr { font-size:12px; color:#64748b; margin-bottom:10px; }
        .sp-popup .info-row { display:flex; justify-content:space-between; font-size:12px; color:#64748b; margin-bottom:8px; }
        .sp-popup .stats { display:flex; gap:6px; margin-bottom:10px; }
        .sp-popup .sbox { flex:1; text-align:center; padding:8px 4px; border-radius:8px; background:#f8fafc; }
        .sp-popup .snum { font-size:20px; font-weight:700; }
        .sp-popup .slbl { font-size:10px; color:#64748b; }
        .sp-popup a.pbtn { display:block; padding:9px; background:#2563eb; color:white; border-radius:8px; font-size:13px; font-weight:700; text-align:center; text-decoration:none; transition:background 0.15s; }
        .sp-popup a.pbtn:hover { background:#1d4ed8; }

        .flash { position:fixed; bottom:20px; right:20px; padding:14px 20px; border-radius:12px; font-size:14px; font-weight:600; z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,0.2); }
        .flash-ok  { background:#dcfce7; color:#166534; }
        .flash-err { background:#fee2e2; color:#991b1b; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/map" class="brand">
        <div class="brand-icon">🅿</div>SmartPark
    </a>
    <div class="nav-links">
        <a href="/map" class="active">🗺 Map</a>
        <a href="/dashboard">Dashboard</a>
        <a href="/locations">Locations</a>
        <a href="/reservations">My Reservations</a>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="/locations/create" class="admin-link">+ Add Location</a>
        @endif
    </div>
    @auth
    <div class="nav-right">
        <a href="{{ route('profile.edit') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; padding:4px 8px; border-radius:8px; transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.07)'" onmouseout="this.style.background='transparent'">
            <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div style="display:flex; flex-direction:column; line-height:1.3;">
                <span class="nav-name">{{ auth()->user()->name }}</span>
                @if(auth()->user()->role === 'admin')
                    <span style="font-size:10px; color:#60a5fa; font-weight:600;">Administrator</span>
                @else
                    <span style="font-size:10px; color:#64748b;">My Profile</span>
                @endif
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
    @endauth
</nav>

@if(session('success'))
    <div class="flash flash-ok" id="flash">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-err" id="flash">❌ {{ session('error') }}</div>
@endif

<div class="app-body">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                Parking Locations
                <span class="sidebar-count" id="countLabel">{{ count($locations) }} locations</span>
            </div>
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" id="searchBox" placeholder="Search by name or city...">
            </div>
            <div class="filter-chips">
                <button class="chip active" data-filter="all">All</button>
                <button class="chip" data-filter="available">Has Spots</button>
                <button class="chip" data-filter="full">Full</button>
            </div>
        </div>

        <div class="loc-list" id="locList">
            @forelse($locations as $loc)
                @php
                    $avail = $loc['stats']['available'];
                    $total = $loc['stats']['total'];
                    $pct   = $total > 0 ? round($avail / $total * 100) : 0;
                    $fc    = $pct > 50 ? '#16a34a' : ($pct > 20 ? '#f59e0b' : '#dc2626');
                @endphp
                <div class="loc-item"
                     data-id="{{ $loc['id'] }}"
                     data-avail="{{ $avail }}"
                     data-total="{{ $total }}"
                     data-name="{{ strtolower($loc['name']) }}"
                     data-city="{{ strtolower($loc['city']) }}"
                     onclick="focusLoc({{ $loc['id'] }})">
                    <div class="loc-item-rate">€{{ number_format($loc['hourly_rate'], 2) }}/h</div>
                    <div class="loc-item-name">{{ $loc['name'] }}</div>
                    <div class="loc-item-addr">📍 {{ $loc['address'] }}, {{ $loc['city'] }}</div>
                    <div class="loc-pills">
                        @if($total === 0)
                            <span class="pill pill-gray">No spots yet</span>
                        @else
                            <span class="pill pill-green">{{ $avail }} free</span>
                            @if($loc['stats']['reserved'] > 0)<span class="pill pill-amber">{{ $loc['stats']['reserved'] }} reserved</span>@endif
                            @if($loc['stats']['occupied'] > 0)<span class="pill pill-red">{{ $loc['stats']['occupied'] }} occupied</span>@endif
                        @endif
                        <span class="pill pill-blue">{{ $loc['opening_hours'] }}</span>
                    </div>
                    @if($total > 0)
                    <div class="avail-bar">
                        <div class="avail-fill" style="width:{{ $pct }}%; background:{{ $fc }};"></div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="no-results">No locations added yet.</div>
            @endforelse
        </div>
    </div>

    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const locations = @json($locations);

const map = L.map('map', { zoomControl: false }).setView([43.8563, 18.4131], 13);
L.control.zoom({ position: 'bottomright' }).addTo(map);

L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap © CARTO',
    maxZoom: 19
}).addTo(map);

const markers = {};

function markerClass(avail, total) {
    if (total === 0) return 'm-gray';
    const p = avail / total;
    return p > 0.5 ? 'm-green' : p > 0.2 ? 'm-amber' : 'm-red';
}

function makeIcon(avail, total) {
    const cls = markerClass(avail, total);
    return L.divIcon({
        html: `<div class="sp-marker ${cls}"><div class="sp-marker-inner">${total > 0 ? avail : '?'}</div></div>`,
        className: '', iconSize: [38, 38], iconAnchor: [19, 38], popupAnchor: [0, -40]
    });
}

function popupHtml(loc) {
    const s = loc.stats;
    const cls = markerClass(s.available, s.total);
    const c = cls === 'm-green' ? '#16a34a' : cls === 'm-amber' ? '#f59e0b' : '#dc2626';
    return `<div class="sp-popup">
        <h3>${loc.name}</h3>
        <div class="addr">📍 ${loc.address}, ${loc.city}</div>
        <div class="info-row">
            <span>⏰ ${loc.opening_hours}</span>
            <span style="font-weight:700;color:#2563eb;">€${parseFloat(loc.hourly_rate).toFixed(2)}/hour</span>
        </div>
        <div class="stats">
            <div class="sbox"><div class="snum" style="color:${c}">${s.available}</div><div class="slbl">Free</div></div>
            <div class="sbox"><div class="snum" style="color:#f59e0b">${s.reserved}</div><div class="slbl">Reserved</div></div>
            <div class="sbox"><div class="snum" style="color:#dc2626">${s.occupied}</div><div class="slbl">Occupied</div></div>
            <div class="sbox"><div class="snum">${s.total}</div><div class="slbl">Total</div></div>
        </div>
        <a href="${loc.url}" class="pbtn">View & Reserve Spots →</a>
    </div>`;
}

locations.forEach(loc => {
    if (loc.latitude && loc.longitude) {
        const m = L.marker([loc.latitude, loc.longitude], {
            icon: makeIcon(loc.stats.available, loc.stats.total)
        }).addTo(map);
        m.bindPopup(popupHtml(loc), { maxWidth: 260 });
        m.on('click', () => highlightSidebar(loc.id));
        markers[loc.id] = m;
    }
});

const mList = Object.values(markers);
if (mList.length > 0) map.fitBounds(L.featureGroup(mList).getBounds().pad(0.25));

function focusLoc(id) {
    highlightSidebar(id);
    if (markers[id]) {
        map.flyTo(markers[id].getLatLng(), 16, { duration: 0.7 });
        setTimeout(() => markers[id].openPopup(), 750);
    }
}

function highlightSidebar(id) {
    document.querySelectorAll('.loc-item').forEach(el => el.classList.remove('active'));
    const el = document.querySelector(`.loc-item[data-id="${id}"]`);
    if (el) { el.classList.add('active'); el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
}

document.getElementById('searchBox').addEventListener('input', applyFilters);
document.querySelectorAll('.chip').forEach(c => {
    c.addEventListener('click', function () {
        document.querySelectorAll('.chip').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
        applyFilters();
    });
});

function applyFilters() {
    const q = document.getElementById('searchBox').value.toLowerCase();
    const f = document.querySelector('.chip.active').dataset.filter;
    let count = 0;
    document.querySelectorAll('.loc-item').forEach(el => {
        const matchQ = el.dataset.name.includes(q) || el.dataset.city.includes(q);
        const avail  = parseInt(el.dataset.avail);
        const total  = parseInt(el.dataset.total);
        const matchF = f === 'all' ? true : f === 'available' ? avail > 0 : (total > 0 && avail === 0);
        const show   = matchQ && matchF;
        el.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('countLabel').textContent = count + ' location' + (count !== 1 ? 's' : '');
}

const flash = document.getElementById('flash');
if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>
</body>
</html>