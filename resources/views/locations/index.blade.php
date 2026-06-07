@extends('layouts.app')
@section('title', 'Locations')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Parking Locations</div>
        <div class="page-subtitle">{{ $locations->count() }} location(s) found</div>
    </div>
    <div class="actions">
        <a href="/map" class="btn btn-secondary">🗺 Map View</a>
        @if(auth()->user()->role === 'admin')
            <a href="/locations/create" class="btn btn-primary">+ Add Location</a>
        @endif
    </div>
</div>

<div class="filter-box">
    <form method="GET" action="/locations">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" placeholder="Name, city, address..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>City</label>
                <select name="city">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group" style="justify-content:flex-end;">
                <label>&nbsp;</label>
                <div class="actions" style="margin:0;">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="/locations" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

@if($locations->count() > 0)
    <div class="grid-cards">
        @foreach($locations as $location)
            @php
                $total = $location->spots->count();
                $avail = $location->spots->where('status', 'available')->count();
                $res   = $location->spots->where('status', 'reserved')->count();
                $occ   = $location->spots->where('status', 'occupied')->count();
                $pct   = $total > 0 ? round($avail / $total * 100) : 0;
                $color = $pct > 50 ? '#16a34a' : ($pct > 20 ? '#f59e0b' : '#dc2626');
            @endphp
            <div class="loc-card">
                <div class="loc-card-header">
                    <div class="loc-card-name">{{ $location->name }}</div>
                    <div class="loc-card-addr">📍 {{ $location->address }}, {{ $location->city }}</div>
                </div>
                <div class="loc-card-body">
                    <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:12px;">
                        @if($total === 0)
                            <span class="badge badge-gray">No spots generated</span>
                        @else
                            <span class="badge badge-green">{{ $avail }} free</span>
                            @if($res > 0)<span class="badge badge-amber">{{ $res }} reserved</span>@endif
                            @if($occ > 0)<span class="badge badge-red">{{ $occ }} occupied</span>@endif
                        @endif
                    </div>
                    @if($total > 0)
                        <div class="avail-bar">
                            <div class="avail-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                        </div>
                        <div class="avail-label">{{ $pct }}% available · {{ $total }} total spots</div>
                    @else
                        <div class="avail-label">Planned capacity: {{ $location->total_spots }}</div>
                    @endif
                    @if($location->hourly_rate)
                        <div class="meta" style="margin-top:8px;">💶 €{{ number_format($location->hourly_rate, 2) }}/hour · ⏰ {{ $location->opening_hours }}</div>
                    @endif
                </div>
                <div class="loc-card-footer">
                    <a href="{{ route('locations.show', $location->id) }}" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">View Spots</a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">🏢</div>
        <div class="empty-title">No locations found</div>
        <div class="empty-text">Try changing your search or add a new location.</div>
        @if(auth()->user()->role === 'admin')
            <a href="/locations/create" class="btn btn-primary">+ Add First Location</a>
        @endif
    </div>
@endif
@endsection