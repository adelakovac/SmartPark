@extends('layouts.app')
@section('title', 'All Reservations')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">All Reservations</div>
        <div class="page-subtitle">{{ $totalReservations }} active reservation(s) across all users</div>
    </div>
    <div class="actions">
        <a href="/admin/users" class="btn btn-secondary">👥 Users</a>
        <a href="/dashboard" class="btn btn-secondary">← Dashboard</a>
    </div>
</div>

@if($expiringCount > 0)
    <div class="alert alert-warn">
        ⚠️ {{ $expiringCount }} reservation(s) are expiring within the next 30 minutes.
    </div>
@endif

{{-- ── STAT CARDS ───────────────────────────────────────────────────────── --}}
@php
    $totalDeposit  = $reservations->sum('deposit_amount');
    $totalRevenue  = $reservations->sum('total_cost');
@endphp

<div class="stat-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 28px;">
    <div class="stat-card blue">
        <div class="stat-label">Total Active</div>
        <div class="stat-number blue">{{ $totalReservations }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-label">Expiring Soon</div>
        <div class="stat-number amber">{{ $expiringCount }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Deposits Collected</div>
        <div class="stat-number green" style="font-size:20px;">
            {{ number_format($totalDeposit, 2) }} KM
        </div>
    </div>
    <div class="stat-card" style="background:#f0fdf4; border-left:4px solid #16a34a;">
        <div class="stat-label">Expected Revenue</div>
        <div class="stat-number" style="color:#16a34a; font-size:20px;">
            {{ number_format($totalRevenue, 2) }} KM
        </div>
    </div>
</div>

{{-- ── TABLE ────────────────────────────────────────────────────────────── --}}
@if($reservations->count() > 0)
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Spot</th>
                        <th>Location</th>
                        <th>Duration</th>
                        <th>Pricing</th>
                        <th>Reserved At</th>
                        <th>Expires At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                        @php
                            $expiresSoon = \Carbon\Carbon::parse($r->expires_at)->diffInMinutes(now()) < 30
                                        && \Carbon\Carbon::parse($r->expires_at)->isFuture();
                        @endphp
                        <tr style="{{ $expiresSoon ? 'background:#fffbeb;' : '' }}">

                            {{-- User --}}
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div style="width:30px; height:30px; border-radius:50%;
                                                background:#2563eb; color:white;
                                                display:flex; align-items:center; justify-content:center;
                                                font-size:12px; font-weight:700; flex-shrink:0;">
                                        {{ strtoupper(substr($r->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; font-size:13px;">{{ $r->user->name ?? 'Unknown' }}</div>
                                        <div style="font-size:11px; color:#64748b;">{{ $r->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Spot --}}
                            <td>
                                <span style="font-weight:700; font-size:15px;">{{ $r->spot->spot_number ?? '—' }}</span>
                                <div style="font-size:11px; color:#64748b;">{{ ucfirst($r->spot->type ?? '') }}</div>
                            </td>

                            {{-- Location --}}
                            <td>
                                <div style="font-weight:600; font-size:13px;">{{ $r->spot->location->name ?? '—' }}</div>
                                <div style="font-size:11px; color:#64748b;">{{ $r->spot->location->city ?? '' }}</div>
                            </td>

                            {{-- Duration --}}
                            <td style="text-align:center;">
                                <span style="font-weight:700; font-size:15px; color:#2563eb;">
                                    {{ $r->duration_hours ?? '—' }}h
                                </span>
                            </td>

                            {{-- Pricing / Deposit --}}
                            <td>
                                @if(($r->total_cost ?? 0) > 0)
                                    <div style="font-size:12px; line-height:1.6;">
                                        <div style="color:#64748b;">
                                            Total:
                                            <strong style="color:#0f172a;">
                                                {{ number_format($r->total_cost, 2) }} KM
                                            </strong>
                                        </div>
                                        <div style="color:#64748b;">
                                            Deposit
                                            <span style="background:#dbeafe; color:#1d4ed8; border-radius:4px; padding:1px 5px; font-size:11px; font-weight:700;">
                                                {{ ($r->deposit_rate ?? 0) * 100 }}%
                                            </span>:
                                            <strong style="color:#2563eb;">
                                                {{ number_format($r->deposit_amount, 2) }} KM
                                            </strong>
                                        </div>
                                        <div style="color:#94a3b8; font-size:11px;">
                                            Remaining: {{ number_format($r->total_cost - $r->deposit_amount, 2) }} KM
                                        </div>
                                    </div>
                                @else
                                    <span style="font-size:12px; color:#94a3b8;">Free parking</span>
                                @endif
                            </td>

                            {{-- Reserved At --}}
                            <td style="font-size:13px; color:#64748b;">
                                {{ \Carbon\Carbon::parse($r->reserved_at)->format('d M Y, H:i') }}
                            </td>

                            {{-- Expires At --}}
                            <td>
                                <div style="font-size:13px; font-weight:600; color:{{ $expiresSoon ? '#dc2626' : '#f59e0b' }};">
                                    {{ \Carbon\Carbon::parse($r->expires_at)->format('d M Y, H:i') }}
                                </div>
                                <div style="font-size:11px; color:#64748b;">
                                    {{ \Carbon\Carbon::parse($r->expires_at)->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($expiresSoon)
                                    <span class="badge badge-red">Expiring soon</span>
                                @else
                                    <span class="badge badge-amber">Active</span>
                                @endif
                            </td>

                            {{-- Cancel --}}
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.reservations.cancel', $r->id) }}"
                                      onsubmit="return confirm('Cancel {{ $r->user->name ?? 'this user' }}\'s reservation for spot {{ $r->spot->spot_number ?? '' }}?');">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">🎫</div>
        <div class="empty-title">No Active Reservations</div>
        <div class="empty-text">There are currently no reservations in the system.</div>
    </div>
@endif

@endsection