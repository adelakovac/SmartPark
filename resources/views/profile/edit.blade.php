@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">My Profile</div>
        <div class="page-subtitle">Manage your account information</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; max-width:900px;">

    {{-- Update Name & Email --}}
    <div class="form-card">
        <div class="card-title">👤 Profile Information</div>

        @if(session('status') === 'profile-updated')
            <div class="alert alert-success" style="margin-bottom:16px;">✅ Profile updated successfully.</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" value="{{ ucfirst(auth()->user()->role ?? 'user') }}" disabled
                       style="background:#f8fafc; color:#64748b;">
            </div>

            <div class="form-group">
                <label>Member Since</label>
                <input type="text" value="{{ auth()->user()->created_at->format('d M Y') }}" disabled
                       style="background:#f8fafc; color:#64748b;">
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="form-card">
        <div class="card-title">🔒 Change Password</div>

        @if(session('status') === 'password-updated')
            <div class="alert alert-success" style="margin-bottom:16px;">✅ Password updated successfully.</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" placeholder="••••••••" required>
                @error('current_password')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" required>
                @error('password')
                    <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat new password" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>

</div>

{{-- Danger Zone --}}
<div class="form-card" style="max-width:900px; margin-top:24px; border-top:3px solid #dc2626;">
    <div class="card-title" style="color:#dc2626;">⚠️ Danger Zone</div>
    <p class="meta" style="margin-bottom:16px;">Once you delete your account, all your reservations will be cancelled and your data will be permanently removed.</p>

    <form method="POST" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Are you sure? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <input type="password" name="password" placeholder="Enter your password to confirm" style="max-width:300px; margin-bottom:12px;">
        @error('password', 'userDeletion')
            <div style="color:#dc2626; font-size:12px; margin-bottom:8px;">{{ $message }}</div>
        @enderror
        <br>
        <button type="submit" class="btn btn-danger">Delete My Account</button>
    </form>
</div>
@endsection