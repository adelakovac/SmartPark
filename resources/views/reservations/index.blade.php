@extends('layouts.app')

@section('content')
    <div class="page-title">My Reservations</div>

    @if($reservations->count() > 0)
        <div class="grid">
            @foreach($reservations as $reservation)
                <div class="card">
                    <h2>Spot {{ $reservation->spot->spot_number }}</h2>

                    <p>Location: {{ $reservation->spot->location->name ?? 'Unknown' }}</p>
                    <p>User: {{ $reservation->user_name }}</p>
                    <p>Reserved at: {{ $reservation->reserved_at }}</p>

                    <form method="POST" action="{{ route('reservations.cancel', $reservation->id) }}">
                        @csrf
                        <button class="btn btn-danger">Cancel Reservation</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p>No reservations yet.</p>
    @endif
@endsection