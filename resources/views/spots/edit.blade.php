@extends('layouts.app')
@section('title', 'Edit Spot')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Spot {{ $spot->spot_number }}</div>
        <div class="page-subtitle">Location: {{ $spot->location->name }}</div>
    </div>
    <div class="actions">
        <a href="{{ route('locations.show', $spot->parking_location_id) }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-wrap">
    <div class="form-card">
        <form method="POST" action="{{ route('spots.update', $spot->id) }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Spot Number *</label>
                    <input type="text" name="spot_number" value="{{ old('spot_number', $spot->spot_number) }}" required>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="available" {{ old('status', $spot->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied"  {{ old('status', $spot->status) == 'occupied'  ? 'selected' : '' }}>Occupied</option>
                        <option value="reserved"  {{ old('status', $spot->status) == 'reserved'  ? 'selected' : '' }}>Reserved</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Spot Type *</label>
                    <select name="type" required>
                        <option value="standard" {{ old('type', $spot->type) == 'standard' ? 'selected' : '' }}>🚗 Standard</option>
                        <option value="electric" {{ old('type', $spot->type) == 'electric' ? 'selected' : '' }}>⚡ Electric</option>
                        <option value="disabled" {{ old('type', $spot->type) == 'disabled' ? 'selected' : '' }}>♿ Disabled</option>
                        <option value="garage"   {{ old('type', $spot->type) == 'garage'   ? 'selected' : '' }}>🏢 Garage</option>
                    </select>
                </div>
            </div>
            <hr class="divider">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Spot</button>
                <a href="{{ route('locations.show', $spot->parking_location_id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection