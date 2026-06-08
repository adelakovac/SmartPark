@extends('layouts.app')
@section('title', $location->name)

@section('content')

<style>
    .spot-grid-dark {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    .spot-card-dark {
        background: #0f172a;
        border: 0.5px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 16px;
        transition: border-color 0.2s, transform 0.15s;
        position: relative;
    }
    .spot-card-dark:hover { border-color: rgba(255,255,255,0.18); transform: translateY(-1px); }
    .spot-card-dark.available { border-top: 2px solid #22c55e; }
    .spot-card-dark.reserved  { border-top: 2px solid #f59e0b; opacity: 0.7; }
    .spot-card-dark.occupied  { border-top: 2px solid #ef4444; opacity: 0.55; }

    .spot-num { font-size: 20px; font-weight: 600; color: white; line-height: 1; margin-bottom: 4px; }
    .spot-typ { font-size: 11px; color: rgba(255,255,255,0.35); margin-bottom: 10px; letter-spacing: 0.3px; text-transform: uppercase; }

    .status-dot { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 500; margin-bottom: 12px; }
    .status-dot::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: block; }
    .status-dot.available { color: #22c55e; }
    .status-dot.available::before { background: #22c55e; }
    .status-dot.reserved  { color: #f59e0b; }
    .status-dot.reserved::before  { background: #f59e0b; }
    .status-dot.occupied  { color: #ef4444; }
    .status-dot.occupied::before  { background: #ef4444; }

    .dur-select {
        width: 100%; font-size: 12px; padding: 7px 10px;
        border: 0.5px solid rgba(255,255,255,0.1); border-radius: 8px;
        margin-bottom: 8px; background: rgba(255,255,255,0.05);
        color: white; outline: none;
    }
    .dur-select:focus { border-color: rgba(37,99,235,0.6); }

    .btn-reserve-dark {
        width: 100%; padding: 8px; background: #2563eb; color: white;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 500;
        cursor: pointer; transition: background 0.15s; font-family: inherit;
    }
    .btn-reserve-dark:hover { background: #1d4ed8; }

    .btn-unavail {
        width: 100%; padding: 8px; background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.25); border: 0.5px solid rgba(255,255,255,0.06);
        border-radius: 8px; font-size: 13px; cursor: not-allowed; font-family: inherit;
    }

    .admin-actions { display: flex; gap: 5px; margin-top: 8px; }
    .admin-btn { flex: 1; padding: 6px 0; font-size: 11px; border-radius: 6px; cursor: pointer; border: none; font-family: inherit; font-weight: 500; transition: all 0.15s; }
    .admin-btn-ghost { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.5); border: 0.5px solid rgba(255,255,255,0.08); }
    .admin-btn-ghost:hover { background: rgba(255,255,255,0.1); color: white; }
    .admin-btn-del { background: rgba(239,68,68,0.1); color: #f87171; border: 0.5px solid rgba(239,68,68,0.2); }
    .admin-btn-del:hover { background: rgba(239,68,68,0.2); }

    .report-btn {
        width: 100%; padding: 6px 0; font-size: 11px; background: transparent;
        border: 0.5px solid rgba(255,255,255,0.06); color: rgba(255,255,255,0.3);
        border-radius: 6px; cursor: pointer; margin-top: 6px; font-family: inherit; transition: all 0.15s;
    }
    .report-btn:hover { border-color: rgba(245,158,11,0.4); color: #fbbf24; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #1e293b; border: 0.5px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 28px; width: 90%; max-width: 400px; }
    .modal-title { font-size: 17px; font-weight: 600; color: white; margin-bottom: 6px; }
    .modal-sub { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 20px; }
    .modal-label { font-size: 12px; color: rgba(255,255,255,0.5); margin-bottom: 6px; display: block; font-weight: 500; }
    .modal-select, .modal-textarea { width: 100%; padding: 10px 14px; border-radius: 10px; background: #0f172a; border: 0.5px solid rgba(255,255,255,0.1); color: white; font-size: 13px; font-family: inherit; outline: none; margin-bottom: 14px; }
    .modal-textarea { min-height: 90px; resize: vertical; }
    .modal-select:focus, .modal-textarea:focus { border-color: rgba(37,99,235,0.6); }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; }
    .modal-cancel { padding: 9px 18px; border-radius: 8px; font-size: 13px; background: transparent; border: 0.5px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); cursor: pointer; font-family: inherit; }
    .modal-submit { padding: 9px 18px; border-radius: 8px; font-size: 13px; background: #f59e0b; border: none; color: #0f172a; cursor: pointer; font-family: inherit; font-weight: 600; }

    .fav-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; font-family: inherit; transition: all 0.15s; }
    .fav-btn.active { background: rgba(239,68,68,0.15); color: #f87171; border: 0.5px solid rgba(239,68,68,0.3); }
    .fav-btn.inactive { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.5); border: 0.5px solid rgba(255,255,255,0.1); }
    .fav-btn.active:hover { background: rgba(239,68,68,0.25); }
    .fav-btn.inactive:hover { background: rgba(255,255,255,0.1); color: white; }

    .dark-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
    .dark-stat { background: #0f172a; border: 0.5px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; }
    .dark-stat-label { font-size: 11px; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .dark-stat-num { font-size: 28px; font-weight: 600; line-height: 1; }

    .dark-filter { background: #0f172a; border: 0.5px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px 18px; margin-bottom: 24px; }
    .dark-filter input, .dark-filter select { background: rgba(255,255,255,0.05); border: 0.5px solid rgba(255,255,255,0.1); color: white; border-radius: 8px; padding: 9px 12px; font-size: 13px; outline: none; }
    .dark-filter input::placeholder { color: rgba(255,255,255,0.25); }
    .dark-filter input:focus, .dark-filter select:focus { border-color: rgba(37,99,235,0.6); }
    .dark-filter select option { background: #1e293b; color: white; }
    .dark-filter label { color: rgba(255,255,255,0.45); font-size: 12px; }

    @media (max-width: 768px) {
        .spot-grid-dark { grid-template-columns: 1fr 1fr; gap: 10px; }
        .dark-stat-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 400px) {
        .spot-grid-dark { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <div>
        <div class="page-title">{{ $location->name }}</div>
        <div class="page-subtitle">
            {{ $location->address }}, {{ $location->city }}
            @if($location->hourly_rate) &nbsp;·&nbsp; €{{ number_format($location->hourly_rate,2) }}/hr @endif
            @if($location->opening_hours) &nbsp;·&nbsp; {{ $location->opening_hours }} @endif
        </div>
    </div>

    {{-- ACTIONS — Save button is here for ALL users, admin buttons below --}}
    <div class="actions">

        {{-- FAVOURITE BUTTON — always visible --}}
        @php $isFav = auth()->user()->hasFavorited($location->id); @endphp
        <form method="POST" action="{{ route('favorites.toggle', $location->id) }}">
            @csrf
            <button type="submit" class="fav-btn {{ $isFav ? 'active' : 'inactive' }}">
                {{ $isFav ? '♥ Saved' : '♡ Save' }}
            </button>
        </form>

        {{-- ADMIN ONLY --}}
        @if(auth()->user()->role === 'admin')
            <a href="/locations/{{ $location->id }}/spots/create" class="btn btn-primary">+ Add Spot</a>
            <form method="POST" action="{{ route('spots.generate', $location->id) }}">
                @csrf
                <button class="btn btn-amber" type="submit">⚡ Generate</button>
            </form>
            <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-secondary">Edit</a>
            <form method="POST" action="{{ route('locations.delete', $location->id) }}"
                  onsubmit="return confirm('Delete {{ $location->name }} and ALL its spots?');">
                @csrf
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        @endif

        <a href="/locations" class="btn btn-secondary">← Back</a>
    </div>
</div>

@if($location->description)
    <div style="background:rgba(37,99,235,0.08); border:0.5px solid rgba(37,99,235,0.2); border-radius:10px; padding:12px 16px; color:#64748b; font-size:14px; margin-bottom:20px;">
        {{ $location->description }}
    </div>
@endif

<div class="dark-stat-grid">
    <div class="dark-stat">
        <div class="dark-stat-label">Total Spots</div>
        <div class="dark-stat-num" style="color:#f1f5f9;">{{ $stats['total'] }}</div>
    </div>
    <div class="dark-stat">
        <div class="dark-stat-label">Available</div>
        <div class="dark-stat-num" style="color:#22c55e;">{{ $stats['available'] }}</div>
    </div>
    <div class="dark-stat">
        <div class="dark-stat-label">Reserved</div>
        <div class="dark-stat-num" style="color:#f59e0b;">{{ $stats['reserved'] }}</div>
    </div>
    <div class="dark-stat">
        <div class="dark-stat-label">Occupied</div>
        <div class="dark-stat-num" style="color:#ef4444;">{{ $stats['occupied'] }}</div>
    </div>
</div>

<div class="dark-filter">
    <form method="GET" action="{{ route('locations.show', $location->id) }}">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Spot</label>
                <input type="text" name="search" placeholder="e.g. A01" value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
                    <option value="reserved"  {{ request('status')=='reserved' ?'selected':'' }}>Reserved</option>
                    <option value="occupied"  {{ request('status')=='occupied' ?'selected':'' }}>Occupied</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="standard" {{ request('type')=='standard'?'selected':'' }}>Standard</option>
                    <option value="electric" {{ request('type')=='electric'?'selected':'' }}>Electric</option>
                    <option value="disabled" {{ request('type')=='disabled'?'selected':'' }}>Disabled</option>
                    <option value="garage"   {{ request('type')=='garage'  ?'selected':'' }}>Garage</option>
                </select>
            </div>
            <div class="filter-group" style="justify-content:flex-end;">
                <label>&nbsp;</label>
                <div class="actions" style="margin:0;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="section-title">
    Parking Spots
    <span style="font-weight:400; font-size:14px; color:#64748b;">({{ $spots->total() }} spots)</span>
</div>

@if($spots->count() > 0)
    <div class="spot-grid-dark">
        @foreach($spots as $spot)
            <div class="spot-card-dark {{ $spot->status }}">
                <div class="spot-num">{{ $spot->spot_number }}</div>
                <div class="spot-typ">{{ $spot->type }}</div>
                <div class="status-dot {{ $spot->status }}">{{ ucfirst($spot->status) }}</div>

                @if($spot->status === 'available')
                    <form method="POST" action="{{ route('spots.reserve', $spot->id) }}">
                        @csrf
                        <select name="duration" class="dur-select">
                            <option value="1">1 hour</option>
                            <option value="2" selected>2 hours</option>
                            <option value="4">4 hours</option>
                            <option value="8">8 hours</option>
                        </select>
                        <button type="submit" class="btn-reserve-dark">Reserve</button>
                    </form>
                @else
                    <button class="btn-unavail" disabled>Unavailable</button>
                @endif

                <button class="report-btn" onclick="openReport({{ $spot->id }}, '{{ $spot->spot_number }}')">
                    Report issue
                </button>

                @if(auth()->user()->role === 'admin')
                    <div class="admin-actions">
                        @if($spot->status !== 'reserved')
                            <form method="POST" action="{{ route('spots.toggle', $spot->id) }}" style="flex:1;">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-ghost" style="width:100%;">Toggle</button>
                            </form>
                        @endif
                        <a href="{{ route('spots.edit', $spot->id) }}" class="admin-btn admin-btn-ghost" style="text-align:center; text-decoration:none; padding:6px 0; display:block; flex:1;">Edit</a>
                        <form method="POST" action="{{ route('spots.delete', $spot->id) }}"
                              onsubmit="return confirm('Delete {{ $spot->spot_number }}?');" style="flex:1;">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-del" style="width:100%;">Del</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="pagination">
        @if($spots->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a class="page-btn" href="{{ $spots->previousPageUrl() }}">← Prev</a>
        @endif
        <span class="page-btn current">{{ $spots->currentPage() }} / {{ $spots->lastPage() }}</span>
        @if($spots->hasMorePages())
            <a class="page-btn" href="{{ $spots->nextPageUrl() }}">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
@else
    <div class="empty-state" style="background:#0f172a; border:0.5px solid rgba(255,255,255,0.08);">
        <div class="empty-icon">🅿</div>
        <div class="empty-title">No spots found</div>
        <div class="empty-text">
            @if(request()->hasAny(['search','status','type']))
                Try resetting your filters.
            @else
                Use "Generate" to automatically create spots for this location.
            @endif
        </div>
    </div>
@endif

<div class="modal-overlay" id="reportModal">
    <div class="modal-box">
        <div class="modal-title">Report an Issue</div>
        <div class="modal-sub" id="reportModalSub">Spot —</div>
        <form method="POST" id="reportForm">
            @csrf
            <label class="modal-label">Issue Type</label>
            <select name="type" class="modal-select" required>
                <option value="" disabled selected>Select issue...</option>
                <option value="damaged">Spot is damaged</option>
                <option value="occupied_wrongly">Occupied without reservation</option>
                <option value="missing_sign">Missing sign or number</option>
                <option value="other">Other</option>
            </select>
            <label class="modal-label">Details (optional)</label>
            <textarea name="message" class="modal-textarea" placeholder="Describe the issue..."></textarea>
            <div class="modal-footer">
                <button type="button" class="modal-cancel" onclick="closeReport()">Cancel</button>
                <button type="submit" class="modal-submit">Submit Report</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReport(spotId, spotNum) {
    document.getElementById('reportModal').classList.add('open');
    document.getElementById('reportModalSub').textContent = 'Spot ' + spotNum;
    document.getElementById('reportForm').action = '/spots/' + spotId + '/report';
}
function closeReport() {
    document.getElementById('reportModal').classList.remove('open');
}
document.getElementById('reportModal').addEventListener('click', function(e) {
    if (e.target === this) closeReport();
});
</script>

@endsection