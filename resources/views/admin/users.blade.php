@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">User Management</div>
        <div class="page-subtitle">{{ $users->count() }} registered user(s)</div>
    </div>
    <div class="actions">
        <a href="/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Reservations</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="color:#94a3b8; font-size:12px;">{{ $user->id }}</td>

                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:50%; background:#2563eb;
                                            display:flex; align-items:center; justify-content:center;
                                            color:white; font-weight:700; font-size:13px; flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:14px;">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <div style="font-size:11px; color:#94a3b8;">You</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td style="font-size:13px; color:#64748b;">{{ $user->email }}</td>

                        <td>
                            @if($user->role === 'admin')
                                <span class="badge" style="background:#ede9fe; color:#5b21b6;">Admin</span>
                            @else
                                <span class="badge badge-gray">User</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-blue">{{ $user->reservations_count }}</span>
                        </td>

                        <td style="font-size:13px; color:#64748b;">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        <td>
                            @if($user->id !== auth()->id())
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    @if($user->role === 'user')
                                        <form method="POST" action="{{ route('admin.users.promote', $user->id) }}">
                                            @csrf
                                            <button class="btn btn-primary btn-sm">
                                                ⬆ Make Admin
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.demote', $user->id) }}">
                                            @csrf
                                            <button class="btn btn-amber btn-sm">
                                                ⬇ Make User
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                                          onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            @else
                                <span style="font-size:12px; color:#94a3b8;">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection