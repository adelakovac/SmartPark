@extends('layouts.app')

@section('content')
    <div class="top-row">
        <div>
            <div class="page-title">{{ $location->name }}</div>
            <div class="subtitle">{{ $location->address }}, {{ $location->city }}</div>
        </div>

        <div class="actions">
            <a href="/locations/{{ $location->id }}/spots/create" class="btn btn-primary">+ Add Parking Spot</a>

            <form method="POST" action="{{ route('spots.generate', $location->id) }}">
                @csrf
                <button class="btn btn-primary" type="submit">⚡ Generate Spots</button>
            </form>

            <a href="/locations" class="btn btn-success">← Back to Locations</a>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="label">Total Spots</div>
            <div class="stat-number">{{ $stats['total'] }}</div>
        </div>

        <div class="card">
            <div class="label">Available Spots</div>
            <div class="stat-number">{{ $stats['available'] }}</div>
        </div>

        <div class="card">
            <div class="label">Occupied Spots</div>
            <div class="stat-number">{{ $stats['occupied'] }}</div>
        </div>

        <div class="card">
            <div class="label">Reserved Spots</div>
            <div class="stat-number">{{ $stats['reserved'] }}</div>
        </div>
    </div>

    <div class="filter-box">
        <form method="GET" action="{{ route('locations.show', $location->id) }}" class="filter-bar">
            <div>
                <label>Search Spot</label>
                <input
                    type="text"
                    name="search"
                    class="small-input"
                    placeholder="Example: A1"
                    value="{{ request('search') }}"
                >
            </div>

            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>

            <div class="actions" style="margin-bottom:0;">
                <button type="submit" class="btn btn-primary">Search / Filter</button>
                <a href="{{ route('locations.show', $location->id) }}" class="btn btn-danger">Reset</a>
            </div>
        </form>
    </div>

    <div class="section-title">Parking Spots</div>

    @if($spots->count() > 0)
        <div class="spot-list">
            @foreach($spots as $spot)
                <div>
                    <form method="POST" action="{{ route('spots.toggle', $spot->id) }}">
                        @csrf
                        <button type="submit" style="all: unset; cursor: pointer; width: 100%;">
                            <div class="spot-card {{ $spot->status }}">
                                <div class="spot-number">{{ $spot->spot_number }}</div>

                                <div class="meta">Type: {{ ucfirst($spot->type) }}</div>

                                <span class="badge {{ $spot->status }}">
                                    {{ ucfirst($spot->status) }}
                                </span>
                            </div>
                        </button>
                    </form>

                    <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                        @if($spot->status === 'available')
                            <form method="POST" action="{{ route('spots.reserve', $spot->id) }}">
                                @csrf
                                <button class="btn btn-success" type="submit">Reserve</button>
                            </form>
                        @else
                            <button class="btn btn-disabled" disabled>Not Available</button>
                        @endif

                        <a href="{{ route('spots.edit', $spot->id) }}" class="btn btn-primary">Edit</a>

                        <form method="POST" action="{{ route('spots.delete', $spot->id) }}" onsubmit="return confirm('Are you sure you want to delete this spot?');">
                            @csrf
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrap">
            @if($spots->onFirstPage())
                <span class="page-link disabled">← Previous</span>
            @else
                <a class="page-link" href="{{ $spots->previousPageUrl() }}">← Previous</a>
            @endif

            <span class="page-link">Page {{ $spots->currentPage() }} / {{ $spots->lastPage() }}</span>

            @if($spots->hasMorePages())
                <a class="page-link" href="{{ $spots->nextPageUrl() }}">Next →</a>
            @else
                <span class="page-link disabled">Next →</span>
            @endif
        </div>
    @else
        <div class="empty-state">
            No parking spots found for this filter.
        </div>
    @endif
@endsection