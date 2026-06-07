@extends('layouts.app')
@section('title', 'Edit Location')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Location</div>
        <div class="page-subtitle">{{ $location->name }}</div>
    </div>
    <div class="actions">
        <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <strong>Please fix the following errors:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-wrap">
    <div class="form-card">
        <form method="POST" action="{{ route('locations.update', $location->id) }}">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Location Name *</label>
                    <input type="text" name="name" value="{{ old('name', $location->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Address *</label>
                    <input type="text" name="address" value="{{ old('address', $location->address) }}" required>
                </div>
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="city" value="{{ old('city', $location->city) }}" required>
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description">{{ old('description', $location->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Total Planned Spots *</label>
                    <input type="number" name="total_spots" min="1" max="1000" value="{{ old('total_spots', $location->total_spots) }}" required>
                </div>
                <div class="form-group">
                    <label>Hourly Rate (€)</label>
                    <input type="number" name="hourly_rate" step="0.50" min="0" value="{{ old('hourly_rate', $location->hourly_rate) }}">
                </div>
                <div class="form-group">
                    <label>Opening Hours</label>
                    <input type="text" name="opening_hours" placeholder="e.g. 07:00 - 23:00" value="{{ old('opening_hours', $location->opening_hours) }}">
                </div>
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="number" name="latitude" step="0.0000001" value="{{ old('latitude', $location->latitude) }}">
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="number" name="longitude" step="0.0000001" value="{{ old('longitude', $location->longitude) }}">
                </div>
            </div>
            <hr class="divider">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection