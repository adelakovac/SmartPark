@extends('layouts.app')
@section('title', 'Add Spot')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add Parking Spot</div>
        <div class="page-subtitle">Adding to: {{ $location->name }}</div>
    </div>
    <div class="actions">
        <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-wrap">
    <div class="form-card">
        <form method="POST" action="/locations/{{ $location->id }}/spots">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Spot Number *</label>
                    <input type="text" name="spot_number" placeholder="e.g. A01, B12" value="{{ old('spot_number') }}" required>
                    <div class="form-hint">Recommended format: letter + number (A01)</div>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied"  {{ old('status') == 'occupied'  ? 'selected' : '' }}>Occupied</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Spot Type *</label>
                    <select name="type" required>
                        <option value="" disabled selected>Select type...</option>
                        <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>🚗 Standard</option>
                        <option value="electric" {{ old('type') == 'electric' ? 'selected' : '' }}>⚡ Electric</option>
                        <option value="disabled" {{ old('type') == 'disabled' ? 'selected' : '' }}>♿ Disabled</option>
                        <option value="garage"   {{ old('type') == 'garage'   ? 'selected' : '' }}>🏢 Garage</option>
                    </select>
                </div>
            </div>
            <hr class="divider">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Spot</button>
                <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection