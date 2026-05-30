@extends('layouts.app')

@section('content')
    <div class="top-row">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="subtitle">SmartPark overview and statistics</div>
        </div>

        <div class="actions">
            <a href="/locations" class="btn btn-primary">Manage Locations</a>
            <a href="/reservations" class="btn btn-success">My Reservations</a>
        </div>
    </div>

    @if($emptyLocations > 0)
        <div class="alert error">
            {{ $emptyLocations }} locations have no generated spots yet.
        </div>
    @endif

    <div class="grid">
        <div class="card">
            <div class="label">Total Locations</div>
            <div class="stat-number">{{ $totalLocations }}</div>
        </div>

        <div class="card">
            <div class="label">Generated Spots</div>
            <div class="stat-number">{{ $totalGeneratedSpots }}</div>
        </div>

        <div class="card">
            <div class="label">Planned Capacity</div>
            <div class="stat-number">{{ $totalPlannedCapacity }}</div>
        </div>

        <div class="card">
            <div class="label">Available Spots</div>
            <div class="stat-number" style="color:#16a34a;">{{ $availableSpots }}</div>
        </div>

        <div class="card">
            <div class="label">Occupied Spots</div>
            <div class="stat-number" style="color:#dc2626;">{{ $occupiedSpots }}</div>
        </div>

        <div class="card">
            <div class="label">Reserved Spots</div>
            <div class="stat-number" style="color:#f59e0b;">{{ $reservedSpots }}</div>
        </div>

        <div class="card">
            <div class="label">Utilization</div>
            <div class="stat-number">{{ $utilization }}%</div>
        </div>
    </div>

    <div class="section-title">Top Locations by Capacity</div>

    @if($topLocations->count() > 0)
        <div class="grid">
            @foreach($topLocations as $location)
                <div class="card">
                    <h2 style="margin-top:0;">{{ $location->name }}</h2>
                    <div class="meta">{{ $location->address }}, {{ $location->city }}</div>
                    <div class="meta" style="margin-top:10px;">
                        Capacity: {{ $location->total_spots }}
                    </div>
                    <div style="margin-top:18px;">
                        <a href="{{ route('locations.show', $location->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            No locations available yet.
        </div>
    @endif
@endsection
