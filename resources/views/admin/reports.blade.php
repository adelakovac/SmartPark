@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Spot Reports</div>
        <div class="page-subtitle">{{ $openCount }} open · {{ $resolvedCount }} resolved</div>
    </div>
    <div class="actions">
        <a href="/dashboard" class="btn btn-secondary">← Dashboard</a>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom:24px;">
    <div class="stat-card amber">
        <div class="stat-label">Open Reports</div>
        <div class="stat-number amber">{{ $openCount }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Resolved</div>
        <div class="stat-number green">{{ $resolvedCount }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total</div>
        <div class="stat-number blue">{{ $reports->count() }}</div>
    </div>
</div>

@if($reports->count() > 0)
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Spot</th>
                        <th>Location</th>
                        <th>Reported By</th>
                        <th>Issue Type</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $r)
                        @php $labels = ['damaged'=>'Damaged','occupied_wrongly'=>'Wrong Occupancy','missing_sign'=>'Missing Sign','other'=>'Other']; @endphp
                        <tr>
                            <td style="font-weight:600;">{{ $r->spot->spot_number ?? '—' }}</td>
                            <td style="font-size:13px; color:#64748b;">{{ $r->spot->location->name ?? '—' }}</td>
                            <td>
                                <div style="font-size:13px; font-weight:500;">{{ $r->user->name ?? '—' }}</div>
                                <div style="font-size:11px; color:#94a3b8;">{{ $r->user->email ?? '' }}</div>
                            </td>
                            <td><span class="badge badge-amber">{{ $labels[$r->type] ?? $r->type }}</span></td>
                            <td style="font-size:13px; color:#64748b; max-width:180px;">{{ $r->message ?? '—' }}</td>
                            <td style="font-size:12px; color:#94a3b8;">{{ $r->created_at->format('d M, H:i') }}</td>
                            <td>
                                @if($r->status === 'open')
                                    <span class="badge badge-red">Open</span>
                                @else
                                    <span class="badge badge-green">Resolved</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px;">
                                    @if($r->status === 'open')
                                        <form method="POST" action="{{ route('admin.reports.resolve', $r->id) }}">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Resolve</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reports.delete', $r->id) }}"
                                          onsubmit="return confirm('Delete this report?');">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">✅</div>
        <div class="empty-title">No Reports</div>
        <div class="empty-text">No issues have been reported yet.</div>
    </div>
@endif
@endsection