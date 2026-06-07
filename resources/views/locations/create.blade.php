@extends('layouts.app')
@section('title', 'Add Location')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add New Location</div>
        <div class="page-subtitle">Create a new parking location in the SmartPark system</div>
    </div>
    <div class="actions">
        <a href="/locations" class="btn btn-secondary">← Back</a>
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
        <form method="POST" action="/locations">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Location Name *</label>
                    <input type="text" name="name" placeholder="e.g. BBI Centar Parking" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Address *</label>
                    <input type="text" name="address" placeholder="e.g. Trg djece Sarajeva bb" value="{{ old('address') }}" required>
                </div>
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="city" placeholder="e.g. Sarajevo" value="{{ old('city') }}" required>
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" placeholder="Optional notes about this location...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Total Planned Spots *</label>
                    <input type="number" name="total_spots" placeholder="e.g. 50" min="1" max="1000" value="{{ old('total_spots') }}" required>
                    <div class="form-hint">You can auto-generate spots after saving.</div>
                </div>
                <div class="form-group">
                    <label>Hourly Rate (€)</label>
                    <input type="number" name="hourly_rate" placeholder="e.g. 2.50" step="0.50" min="0" value="{{ old('hourly_rate', '2.00') }}">
                </div>
                <div class="form-group">
                    <label>Opening Hours</label>
                    <input type="text" name="opening_hours" placeholder="e.g. 07:00 - 23:00" value="{{ old('opening_hours', '00:00 - 24:00') }}">
                </div>
                <div class="form-group">
                    <label>Latitude (map pin)</label>
                    <input type="number" name="latitude" placeholder="e.g. 43.8563" step="0.0000001" value="{{ old('latitude') }}">
                    <div class="form-hint">Right-click on Google Maps → "What's here?"</div>
                </div>
                <div class="form-group">
                    <label>Longitude (map pin)</label>
                    <input type="number" name="longitude" placeholder="e.g. 18.4131" step="0.0000001" value="{{ old('longitude') }}">
                </div>
            </div>
            <hr class="divider">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Location</button>
                <a href="/locations" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection