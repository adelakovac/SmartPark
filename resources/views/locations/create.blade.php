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

<style>
    .field-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .field-error::before {
        content: '⚠';
    }
    input.is-invalid,
    textarea.is-invalid,
    select.is-invalid {
        border-color: #dc2626 !important;
        background: rgba(220, 38, 38, 0.04) !important;
    }
    input.is-valid,
    textarea.is-valid {
        border-color: #16a34a !important;
    }
    .form-hint {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 4px;
    }
    .char-count {
        font-size: 11px;
        color: #94a3b8;
        text-align: right;
        margin-top: 2px;
    }
    .char-count.warn { color: #f59e0b; }
    .char-count.over { color: #dc2626; }
</style>

<div class="form-wrap">
    <div class="form-card">
        <form method="POST" action="/locations" id="locationForm" novalidate>
            @csrf
            <div class="form-grid">

                {{-- NAME --}}
                <div class="form-group full">
                    <label>Location Name <span style="color:#dc2626">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="e.g. BBI Centar Parking"
                        value="{{ old('name') }}"
                        maxlength="255"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                        required>
                    <div class="char-count" id="name-count"></div>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ADDRESS --}}
                <div class="form-group">
                    <label>Address <span style="color:#dc2626">*</span></label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        placeholder="e.g. Trg djece Sarajeva bb"
                        value="{{ old('address') }}"
                        maxlength="255"
                        class="{{ $errors->has('address') ? 'is-invalid' : '' }}"
                        required>
                    @error('address')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CITY --}}
                <div class="form-group">
                    <label>City <span style="color:#dc2626">*</span></label>
                    <input
                        type="text"
                        name="city"
                        id="city"
                        placeholder="e.g. Sarajevo"
                        value="{{ old('city') }}"
                        maxlength="255"
                        pattern="^[A-Za-zÀ-ž\s\-'\.]+$"
                        class="{{ $errors->has('city') ? 'is-invalid' : '' }}"
                        required>
                    <div class="form-hint">Letters only, no numbers.</div>
                    @error('city')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="form-group full">
                    <label>Description</label>
                    <textarea
                        name="description"
                        id="description"
                        placeholder="Optional notes about this location..."
                        maxlength="1000"
                        class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                    <div class="char-count" id="desc-count"></div>
                    @error('description')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TOTAL SPOTS --}}
                <div class="form-group">
                    <label>Total Planned Spots <span style="color:#dc2626">*</span></label>
                    <input
                        type="number"
                        name="total_spots"
                        id="total_spots"
                        placeholder="e.g. 50"
                        min="1"
                        max="1000"
                        value="{{ old('total_spots') }}"
                        class="{{ $errors->has('total_spots') ? 'is-invalid' : '' }}"
                        required>
                    <div class="form-hint">Must be between 1 and 1000. You can auto-generate spots after saving.</div>
                    @error('total_spots')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- HOURLY RATE --}}
                <div class="form-group">
                    <label>Hourly Rate (KM)</label>
                    <input
                        type="number"
                        name="hourly_rate"
                        id="hourly_rate"
                        placeholder="e.g. 2.00"
                        step="0.50"
                        min="0"
                        max="999"
                        value="{{ old('hourly_rate', '2.00') }}"
                        class="{{ $errors->has('hourly_rate') ? 'is-invalid' : '' }}">
                    <div class="form-hint">Enter 0 for free parking. Max 999 KM/hr.</div>
                    @error('hourly_rate')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- OPENING HOURS --}}
                <div class="form-group">
                    <label>Opening Hours</label>
                    <input
                        type="text"
                        name="opening_hours"
                        id="opening_hours"
                        placeholder="e.g. 07:00 - 23:00"
                        value="{{ old('opening_hours', '00:00 - 24:00') }}"
                        maxlength="13"
                        class="{{ $errors->has('opening_hours') ? 'is-invalid' : '' }}">
                    <div class="form-hint">Format: <strong>HH:MM - HH:MM</strong> (e.g. 07:00 - 23:00 or 00:00 - 24:00)</div>
                    <div class="field-error" id="hours-error" style="display:none;"></div>
                    @error('opening_hours')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- LATITUDE --}}
                <div class="form-group">
                    <label>Latitude</label>
                    <input
                        type="number"
                        name="latitude"
                        id="latitude"
                        placeholder="e.g. 43.8563"
                        step="0.0000001"
                        min="-90"
                        max="90"
                        value="{{ old('latitude') }}"
                        class="{{ $errors->has('latitude') ? 'is-invalid' : '' }}">
                    <div class="form-hint">Between -90 and 90. Right-click on Google Maps → "What's here?"</div>
                    @error('latitude')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- LONGITUDE --}}
                <div class="form-group">
                    <label>Longitude</label>
                    <input
                        type="number"
                        name="longitude"
                        id="longitude"
                        placeholder="e.g. 18.4131"
                        step="0.0000001"
                        min="-180"
                        max="180"
                        value="{{ old('longitude') }}"
                        class="{{ $errors->has('longitude') ? 'is-invalid' : '' }}">
                    <div class="form-hint">Between -180 and 180.</div>
                    @error('longitude')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <hr class="divider">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">Save Location</button>
                <a href="/locations" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// ─── Character counters ───────────────────────────────────────────────────
function charCount(inputId, countId, max) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(countId);
    if (!input || !counter) return;

    function update() {
        const len = input.value.length;
        counter.textContent = len + ' / ' + max;
        counter.className = 'char-count';
        if (len > max * 0.9) counter.classList.add('warn');
        if (len >= max)      counter.classList.add('over');
    }
    input.addEventListener('input', update);
    update();
}

charCount('name', 'name-count', 255);
charCount('description', 'desc-count', 1000);

// ─── Opening hours live validation ────────────────────────────────────────
const hoursInput = document.getElementById('opening_hours');
const hoursError = document.getElementById('hours-error');
const hoursRegex = /^([01]?[0-9]|2[0-4]):[0-5][0-9] - ([01]?[0-9]|2[0-4]):[0-5][0-9]$/;

// Auto-format: insert colon and dash as user types
hoursInput.addEventListener('input', function () {
    let v = this.value.replace(/[^0-9:\- ]/g, ''); // strip invalid chars
    this.value = v;
    validateHours();
});

hoursInput.addEventListener('blur', validateHours);

function validateHours() {
    const v = hoursInput.value.trim();
    if (v === '') {
        hoursError.style.display = 'none';
        hoursInput.classList.remove('is-invalid', 'is-valid');
        return true;
    }
    if (!hoursRegex.test(v)) {
        hoursError.textContent = 'Invalid format. Use HH:MM - HH:MM (e.g. 07:00 - 23:00)';
        hoursError.style.display = 'flex';
        hoursInput.classList.add('is-invalid');
        hoursInput.classList.remove('is-valid');
        return false;
    }
    // Check open time is before close time (allow 00:00 - 24:00)
    const parts = v.split(' - ');
    const [oh, om] = parts[0].split(':').map(Number);
    const [ch, cm] = parts[1].split(':').map(Number);
    const openMins  = oh * 60 + om;
    const closeMins = ch * 60 + cm;
    // Allow 00:00 - 24:00 as special case (24/7)
    const is2400 = (ch === 24 && cm === 0);
    if (!is2400 && closeMins <= openMins) {
        hoursError.textContent = 'Closing time must be after opening time.';
        hoursError.style.display = 'flex';
        hoursInput.classList.add('is-invalid');
        hoursInput.classList.remove('is-valid');
        return false;
    }
    hoursError.style.display = 'none';
    hoursInput.classList.remove('is-invalid');
    hoursInput.classList.add('is-valid');
    return true;
}

// ─── City: letters only ───────────────────────────────────────────────────
document.getElementById('city').addEventListener('input', function () {
    // Remove digits but keep letters, spaces, hyphens, apostrophes, dots
    this.value = this.value.replace(/[0-9]/g, '');
});

// ─── Lat/Lng: clamp on blur ───────────────────────────────────────────────
document.getElementById('latitude').addEventListener('blur', function () {
    if (this.value === '') return;
    const v = parseFloat(this.value);
    if (v < -90)  this.value = '-90';
    if (v >  90)  this.value = '90';
});
document.getElementById('longitude').addEventListener('blur', function () {
    if (this.value === '') return;
    const v = parseFloat(this.value);
    if (v < -180) this.value = '-180';
    if (v >  180) this.value = '180';
});

// ─── Hourly rate: no negatives ────────────────────────────────────────────
document.getElementById('hourly_rate').addEventListener('blur', function () {
    if (this.value === '') return;
    if (parseFloat(this.value) < 0) this.value = '0';
});

// ─── Total spots: clamp ───────────────────────────────────────────────────
document.getElementById('total_spots').addEventListener('blur', function () {
    if (this.value === '') return;
    const v = parseInt(this.value);
    if (v < 1)    this.value = '1';
    if (v > 1000) this.value = '1000';
});

// ─── Form submit: run all checks before sending ───────────────────────────
document.getElementById('locationForm').addEventListener('submit', function (e) {
    let valid = true;

    // Required fields check
    ['name', 'address', 'city', 'total_spots'].forEach(function (id) {
        const el = document.getElementById(id);
        if (!el.value.trim()) {
            el.classList.add('is-invalid');
            valid = false;
        } else {
            el.classList.remove('is-invalid');
        }
    });

    // Opening hours
    if (!validateHours()) valid = false;

    // Lat must come with Lng and vice versa
    const lat = document.getElementById('latitude').value.trim();
    const lng = document.getElementById('longitude').value.trim();
    if ((lat && !lng) || (!lat && lng)) {
        alert('Please enter both Latitude and Longitude, or leave both empty.');
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
        // Scroll to first error
        const firstErr = document.querySelector('.is-invalid');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection