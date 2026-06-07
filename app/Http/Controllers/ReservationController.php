<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ParkingSpot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        Reservation::where('expires_at', '<', now())->each(function ($r) {
            if ($r->spot) {
                $r->spot->update(['status' => 'available']);
            }
            $r->delete();
        });

        $reservations = Reservation::with(['spot.location'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function adminIndex()
    {
        // Clean expired first
        Reservation::where('expires_at', '<', now())->each(function ($r) {
            if ($r->spot) {
                $r->spot->update(['status' => 'available']);
            }
            $r->delete();
        });

        $reservations = Reservation::with(['spot.location', 'user'])
            ->latest()
            ->get();

        $totalReservations = $reservations->count();
        $expiringCount = $reservations->filter(function ($r) {
            return \Carbon\Carbon::parse($r->expires_at)->diffInMinutes(now()) < 30
                && \Carbon\Carbon::parse($r->expires_at)->isFuture();
        })->count();

        return view('admin.reservations', compact('reservations', 'totalReservations', 'expiringCount'));
    }

    public function adminCancel($id)
    {
        $reservation = Reservation::with('spot')->findOrFail($id);

        DB::transaction(function () use ($reservation) {
            if ($reservation->spot) {
                $reservation->spot->update(['status' => 'available']);
            }
            $reservation->delete();
        });

        return redirect()->back()->with('success', 'Reservation cancelled and spot freed.');
    }

    public function store($spotId)
    {
        try {
            DB::transaction(function () use ($spotId) {
                $spot = ParkingSpot::lockForUpdate()->findOrFail($spotId);

                if ($spot->status !== 'available') {
                    throw new \Exception('This spot is no longer available.');
                }

                $spot->update(['status' => 'reserved']);

                Reservation::create([
                    'parking_spot_id' => $spot->id,
                    'user_id'         => auth()->id(),
                    'user_name'       => auth()->user()->name,
                    'reserved_at'     => now(),
                    'expires_at'      => now()->addHours(2),
                ]);
            });

            return redirect()->back()->with('success', 'Spot reserved! You have 2 hours before it expires.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        DB::transaction(function () use ($reservation) {
            if ($reservation->spot) {
                $reservation->spot->update(['status' => 'available']);
            }
            $reservation->delete();
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled successfully.');
    }
}