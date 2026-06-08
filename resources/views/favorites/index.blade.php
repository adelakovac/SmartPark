@extends('layouts.app')
@section('title', 'My Favourites')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">My Favourites</div>
        <div class="page-subtitle">{{ $favorites->count() }} saved location(s)</div>
    </div>
    <div class="actions">
        <a href="/locations" class="btn btn-secondary">Browse All Locations</a>
    </div>
</div>

@if($favorites->count() > 0)
    <div class="grid-cards">
        @foreach($favorites as $fav)
            @php
                $loc   = $fav->location;
                $total = $loc->spots->count();
                $avail = $loc->spots->where('status','available')->count();
                $pct   = $total > 0 ? round($avail / $total * 100) : 0;
                $color = $pct > 50 ? '#16a34a' : ($pct > 20 ? '#f59e0b' : '#dc2626');
            @endphp
            <div class="loc-card">
                <div class="loc-card-header">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div class="loc-card-name">{{ $loc->name }}</div>
                        <form method="POST" action="{{ route('favorites.toggle', $loc->id) }}">
                            @csrf
                            <button type="submit" style="background:rgba(239,68,68,0.2); border:none; color:#f87171; border-radius:6px; padding:4px 8px; font-size:13px; cursor:pointer;">♥</button>
                        </form>
                    </div>
                    <div class="loc-card-addr">📍 {{ $loc->address }}, {{ $loc->city }}</div>
                </div>
                <div class="loc-card-body">
                    <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:10px;">
                        @if($total === 0)
                            <span class="badge badge-gray">No spots</span>
                        @else
                            <span class="badge badge-green">{{ $avail }} free</span>
                        @endif
                    </div>
                    @if($total > 0)
                        <div class="avail-bar">
                            <div class="avail-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                        </div>
                        <div class="avail-label">{{ $pct }}% available · {{ $total }} total</div>
                    @endif
                    @if($loc->hourly_rate)
                        <div class="meta" style="margin-top:8px;">💶 €{{ number_format($loc->hourly_rate,2) }}/hr · ⏰ {{ $loc->opening_hours }}</div>
                    @endif
                </div>
                <div class="loc-card-footer">
                    <a href="{{ route('locations.show', $loc->id) }}" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">View Spots</a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">♡</div>
        <div class="empty-title">No Saved Locations</div>
        <div class="empty-text">Browse parking locations and click the heart button to save your favourites.</div>
        <a href="/locations" class="btn btn-primary">Browse Locations</a>
    </div>
@endif
@endsection