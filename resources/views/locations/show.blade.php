@extends('layouts.app')
@section('title', $location->name)

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $location->name }}</div>
        <div class="page-subtitle">
            📍 {{ $location->address }}, {{ $location->city }}
            @if($location->hourly_rate) · 💶 €{{ number_format($location->hourly_rate, 2) }}/hr @endif
            @if($location->opening_hours) · ⏰ {{ $location->opening_hours }} @endif
        </div>
    </div>
    <div class="actions">
        @if(auth()->user()->role === 'admin')
            <a href="/locations/{{ $location->id }}/spots/create" class="btn btn-primary">+ Add Spot</a>
            <form method="POST" action="{{ route('spots.generate', $location->id) }}">
                @csrf
                <button class="btn btn-amber" type="submit">⚡ Generate Spots</button>
            </form>
            <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-secondary">✏️ Edit</a>
            <form method="POST" action="{{ route('locations.delete', $location->id) }}"
                  onsubmit="return confirm('Delete {{ $location->name }} and ALL its spots? This cannot be undone.');">
                @csrf
                <button class="btn btn-danger" type="submit">🗑 Delete</button>
            </form>
        @endif
        <a href="/locations" class="btn btn-secondary">← Back</a>
    </div>
</div>

@if($location->description)
    <div class="alert" style="background:#f8fafc; border-left:4px solid #3b82f6; color:#374151; margin-bottom:20px;">
        ℹ️ {{ $location->description }}
    </div>
@endif

<div class="stat-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card navy">
        <div class="stat-label">Total Spots</div>
        <div class="stat-number">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Available</div>
        <div class="stat-number green">{{ $stats['available'] }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-label">Reserved</div>
        <div class="stat-number amber">{{ $stats['reserved'] }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Occupied</div>
        <div class="stat-number red">{{ $stats['occupied'] }}</div>
    </div>
</div>

<div class="filter-box">
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
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved"  {{ request('status') == 'reserved'  ? 'selected' : '' }}>Reserved</option>
                    <option value="occupied"  {{ request('status') == 'occupied'  ? 'selected' : '' }}>Occupied</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="standard" {{ request('type') == 'standard' ? 'selected' : '' }}>🚗 Standard</option>
                    <option value="electric" {{ request('type') == 'electric' ? 'selected' : '' }}>⚡ Electric</option>
                    <option value="disabled" {{ request('type') == 'disabled' ? 'selected' : '' }}>♿ Disabled</option>
                    <option value="garage"   {{ request('type') == 'garage'   ? 'selected' : '' }}>🏢 Garage</option>
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

<div class="section-title">Parking Spots
    <span style="font-weight:400; font-size:14px; color:#64748b;">({{ $spots->total() }} spots)</span>
</div>

@if($spots->count() > 0)
    <div class="spot-grid">
        @foreach($spots as $spot)
            @php
                $icons = ['standard'=>'🚗','electric'=>'⚡','disabled'=>'♿','garage'=>'🏢'];
                $icon  = $icons[$spot->type] ?? '🚗';
            @endphp
            <div class="spot-card {{ $spot->status }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div class="spot-number-text">{{ $spot->spot_number }}</div>
                    <span style="font-size:18px;">{{ $icon }}</span>
                </div>
                <div class="spot-type">{{ ucfirst($spot->type) }}</div>
                @if($spot->status === 'available')
                    <span class="badge badge-green">Available</span>
                @elseif($spot->status === 'reserved')
                    <span class="badge badge-amber">Reserved</span>
                @else
                    <span class="badge badge-red">Occupied</span>
                @endif

                <div class="spot-actions">
                    @if($spot->status === 'available')
                        <form method="POST" action="{{ route('spots.reserve', $spot->id) }}" style="width:100%;">
                            @csrf
                            <select name="duration" style="width:100%; margin-bottom:6px; padding:6px 10px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:12px; font-weight:600; color:#374151; background:white; outline:none;">
                                <option value="1">⏱ 1 hour</option>
                                <option value="2" selected>⏱ 2 hours</option>
                                <option value="4">⏱ 4 hours</option>
                                <option value="8">⏱ 8 hours</option>
                            </select>
                            <button class="btn btn-success btn-sm" style="width:100%; justify-content:center;">Reserve</button>
                        </form>
                    @else
                        <span class="btn btn-disabled btn-sm" style="width:100%; justify-content:center;">Unavailable</span>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        @if($spot->status !== 'reserved')
                            <form method="POST" action="{{ route('spots.toggle', $spot->id) }}">
                                @csrf
                                <button class="btn btn-secondary btn-sm">Toggle</button>
                            </form>
                        @endif
                        <a href="{{ route('spots.edit', $spot->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('spots.delete', $spot->id) }}"
                              onsubmit="return confirm('Delete spot {{ $spot->spot_number }}?');">
                            @csrf
                            <button class="btn btn-danger btn-sm">Del</button>
                        </form>
                    @endif
                </div>
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
    <div class="empty-state">
        <div class="empty-icon">🅿️</div>
        <div class="empty-title">No spots found</div>
        <div class="empty-text">
            @if(request()->hasAny(['search','status','type']))
                Try resetting your filters.
            @else
                Use "Generate Spots" to automatically create spots for this location.
            @endif
        </div>
    </div>
@endif
@endsection