@extends('layouts.app')

@section('content')
    <div class="top-row">
        <div>
            <div class="page-title">Parking Locations</div>
            <div class="subtitle">Manage all SmartPark parking locations</div>
        </div>

        <div class="actions">
            <a href="/locations/create" class="btn btn-primary">+ Add New Location</a>
        </div>
    </div>

    @if($locations->count() > 0)
        <div class="grid">
            @foreach($locations as $location)
                <div class="card">
                    <h2 style="margin-top:0;">{{ $location->name }}</h2>
                    <div class="meta">{{ $location->address }}, {{ $location->city }}</div>
                    <div class="meta" style="margin-top:10px;">
                        Planned capacity: {{ $location->total_spots }}
                    </div>

                    <div style="margin-top:18px;">
                        <a href="{{ route('locations.show', $location->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            No parking locations yet.
        </div>
    @endif
@endsection