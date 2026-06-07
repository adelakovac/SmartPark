@extends('layouts.app')
@section('title', 'My Reservations')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">My Reservations</div>
        <div class="page-subtitle">{{ $reservations->count() }} active reservation(s)</div>
    </div>
    <div class="actions">
        <a href="/map" class="btn btn-primary">🗺 Find More Parking</a>
    </div>
</div>

@if($reservations->count() > 0)
    <div class="grid-cards">
        @foreach($reservations as $r)
            @php
                $icons = ['standard'=>'🚗','electric'=>'⚡','disabled'=>'♿','garage'=>'🏢'];
                $icon  = $icons[$r->spot->type ?? 'standard'] ?? '🚗';
                $expiresSoon = \Carbon\Carbon::parse($r->expires_at)->diffInMinutes(now()) < 30;
            @endphp
            <div class="card" style="border-top:4px solid #f59e0b;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div>
                        <div style="font-size:26px; font-weight:700;">{{ $icon }} Spot {{ $r->spot->spot_number }}</div>
                        <div class="meta">{{ $r->spot->location->name ?? 'Unknown Location' }}</div>
                    </div>
                    <span class="badge badge-amber">Active</span>
                </div>

                <hr class="divider" style="margin:12px 0;">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px;">
                    <div>
                        <div class="meta">Type</div>
                        <div style="font-weight:600; font-size:14px;">{{ ucfirst($r->spot->type ?? 'standard') }}</div>
                    </div>
                    <div>
                        <div class="meta">Address</div>
                        <div style="font-weight:600; font-size:13px;">{{ $r->spot->location->address ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="meta">Reserved at</div>
                        <div style="font-weight:600; font-size:13px;">{{ \Carbon\Carbon::parse($r->reserved_at)->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <div class="meta">Expires at</div>
                        <div style="font-weight:600; font-size:13px; color:{{ $expiresSoon ? '#dc2626' : '#f59e0b' }};">
                            ⏳ {{ \Carbon\Carbon::parse($r->expires_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <div style="background:#fef3c7; border-radius:8px; padding:10px 12px; margin-bottom:14px; font-size:12px; color:#92400e;">
                    ⏰ Expires {{ \Carbon\Carbon::parse($r->expires_at)->diffForHumans() }}
                </div>

                <form method="POST" action="{{ route('reservations.cancel', $r->id) }}" onsubmit="return confirm('Cancel this reservation?');">
                    @csrf
                    <button class="btn btn-danger" type="submit" style="width:100%; justify-content:center;">Cancel Reservation</button>
                </form>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">🎫</div>
        <div class="empty-title">No Active Reservations</div>
        <div class="empty-text">You don't have any reservations. Find a spot to get started.</div>
        <a href="/map" class="btn btn-primary">🗺 Browse Parking Map</a>
    </div>
@endif
@endsection