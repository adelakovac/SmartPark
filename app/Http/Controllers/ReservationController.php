<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ParkingSpot;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
{
    // 🔥 AUTO CLEAN EXPIRED RESERVATIONS
    Reservation::where('expires_at', '<', now())->each(function ($reservation) {
        $spot = $reservation->spot;

        if ($spot) {
            $spot->status = 'available';
            $spot->save();
        }

        $reservation->delete();
    });

    
    $reservations = Reservation::with(['spot.location', 'user'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('reservations.index', compact('reservations'));
}
    public function store($spotId)
    {
        $spot = ParkingSpot::findOrFail($spotId);

        if ($spot->status !== 'available') {
            return redirect()->back()->with('error', 'Spot is not available!');
        }

        $alreadyReserved = Reservation::where('parking_spot_id', $spot->id)->exists();

        if ($alreadyReserved) {
            return redirect()->back()->with('error', 'Spot is already reserved!');
        }

        $spot->status = 'reserved';
        $spot->save();

        Reservation::create([
    'parking_spot_id' => $spot->id,
    'user_id' => auth()->id(),
    'user_name' => auth()->user()->name,
    'reserved_at' => now(),
    'expires_at' => now()->addHours(2),
]);

        return redirect()->back()->with('success', 'Spot reserved successfully!');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not allowed to cancel this reservation.');
        }

        $spot = ParkingSpot::findOrFail($reservation->parking_spot_id);
        $spot->status = 'available';
        $spot->save();

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled successfully!');
    }
}
