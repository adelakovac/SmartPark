@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">Welcome back, {{ auth()->user()->name }} — SmartPark overview</div>
    </div>
    <div class="actions">
        <a href="/map" class="btn btn-primary">🗺 Open Map</a>
        <a href="/locations" class="btn btn-secondary">All Locations</a>
    </div>
</div>

@if($emptyLocations > 0)
    <div class="alert alert-warn">⚠️ {{ $emptyLocations }} location(s) have no parking spots generated yet.</div>
@endif

<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-label">Locations</div>
        <div class="stat-number blue">{{ $totalLocations }}</div>
    </div>
    <div class="stat-card navy">
        <div class="stat-label">Total Spots</div>
        <div class="stat-number">{{ $totalGeneratedSpots }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Available Now</div>
        <div class="stat-number green">{{ $availableSpots }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-label">Reserved</div>
        <div class="stat-number amber">{{ $reservedSpots }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Occupied</div>
        <div class="stat-number red">{{ $occupiedSpots }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Utilization</div>
        <div class="stat-number blue">{{ $utilization }}%</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:28px;">

    <div class="card">
        <div class="card-title">📍 Top Locations by Capacity</div>
        @forelse($topLocations as $loc)
            @php
                $avail = $loc->available_spots_count ?? 0;
                $total = $loc->spots_count ?? 0;
                $pct   = $total > 0 ? round($avail / $total * 100) : 0;
                $color = $pct > 50 ? '#16a34a' : ($pct > 20 ? '#f59e0b' : '#dc2626');
            @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <a href="{{ route('locations.show', $loc->id) }}" style="font-size:14px; font-weight:600; color:#0f172a; text-decoration:none;">{{ $loc->name }}</a>
                    <span style="font-size:12px; color:#64748b;">{{ $avail }} / {{ $total }} free</span>
                </div>
                <div class="avail-bar">
                    <div class="avail-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                </div>
                <div class="avail-label">{{ $loc->city }} · Capacity {{ $loc->total_spots }}</div>
            </div>
        @empty
            <div class="meta">No locations yet.</div>
        @endforelse
    </div>

    <div class="card">
        <div class="card-title" style="display:flex; justify-content:space-between; align-items:center;">
            🎫 My Recent Reservations
            <a href="/reservations" class="btn btn-secondary btn-sm">View All</a>
        </div>
        @forelse($myReservations as $r)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f1f5f9;">
                <div>
                    <div style="font-size:14px; font-weight:600;">Spot {{ $r->spot->spot_number }}</div>
                    <div class="meta">{{ $r->spot->location->name ?? '—' }}</div>
                </div>
                <div style="text-align:right;">
                    <span class="badge badge-amber">Active</span>
                    <div class="meta" style="margin-top:4px;">⏳ {{ \Carbon\Carbon::parse($r->expires_at)->format('H:i') }}</div>
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:24px; border:1px dashed #e2e8f0; border-radius:10px;">
                <div style="font-size:28px; margin-bottom:8px;">🎫</div>
                <div class="meta">No active reservations.</div>
                <a href="/map" class="btn btn-primary btn-sm" style="margin-top:12px; display:inline-flex;">Find Parking</a>
            </div>
        @endforelse
    </div>

</div>
@endsection