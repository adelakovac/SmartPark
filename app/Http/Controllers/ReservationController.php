<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    // Deposit percentages based on duration
    // Longer reservation = higher deposit (more commitment required)
    const DEPOSIT_RATES = [
        1 => 0.10,  // 1 hour  → 10% deposit
        2 => 0.20,  // 2 hours → 20% deposit
        4 => 0.35,  // 4 hours → 35% deposit
        8 => 0.50,  // 8 hours → 50% deposit
    ];

    const MAX_ACTIVE_RESERVATIONS = 1;

    private function cleanupExpired(): void
    {
        Reservation::where('expires_at', '<', now())->each(function ($r) {
            if ($r->spot) {
                $r->spot->update(['status' => 'available']);
            }
            $r->delete();
        });
    }

    public function index()
    {
        $this->cleanupExpired();

        $reservations = Reservation::with(['spot.location'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function adminIndex()
    {
        $this->cleanupExpired();

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

    public function store(Request $request, $spotId)
    {
        $validated = $request->validate([
            'duration' => 'required|in:1,2,4,8',
        ]);

        $hours = (int) $validated['duration'];

        // Check active reservation limit
        $activeCount = Reservation::where('user_id', auth()->id())
            ->where('expires_at', '>', now())
            ->count();

        if ($activeCount >= self::MAX_ACTIVE_RESERVATIONS) {
            return redirect()->back()->with(
                'error',
                'You already have an active reservation. You can only hold 1 reservation at a time. Please cancel your current reservation before making a new one.'
            );
        }

        try {
            DB::transaction(function () use ($spotId, $hours) {
                $spot = ParkingSpot::lockForUpdate()->findOrFail($spotId);

                if ($spot->status !== 'available') {
                    throw new \Exception('This spot is no longer available.');
                }

                // Calculate deposit
                $hourlyRate    = $spot->location->hourly_rate ?? 0;
                $totalCost     = $hourlyRate * $hours;
                $depositRate   = self::DEPOSIT_RATES[$hours];
                $depositAmount = round($totalCost * $depositRate, 2);

                $spot->update(['status' => 'reserved']);

                Reservation::create([
                    'parking_spot_id' => $spot->id,
                    'user_id'         => auth()->id(),
                    'user_name'       => auth()->user()->name,
                    'reserved_at'     => now(),
                    'expires_at'      => now()->addHours($hours),
                    'duration_hours'  => $hours,
                    'total_cost'      => $totalCost,
                    'deposit_amount'  => $depositAmount,
                    'deposit_rate'    => $depositRate,
                ]);
            });

            $depositRate = self::DEPOSIT_RATES[$hours] * 100;
            return redirect()->back()->with(
                'success',
                "Spot reserved for {$hours} hour(s)! A deposit of {$depositRate}% of the total cost has been charged. Your reservation will expire automatically if unused."
            );
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