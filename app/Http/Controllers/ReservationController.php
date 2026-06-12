<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    const MAX_ACTIVE_RESERVATIONS = 1;

    /**
     * Calculate deposit rate based on how far in advance the user is reserving.
     * The earlier you reserve, the higher the deposit (more commitment required).
     *
     * 0–30 min  in advance → 10%
     * 30min–2h  in advance → 20%
     * 2h–6h     in advance → 35%
     * 6h+       in advance → 50%
     */
    private function calculateDepositRate(Carbon $arrivalTime): float
    {
        $minutesInAdvance = now()->diffInMinutes($arrivalTime, false);

        // If arrival is in the past or right now, treat as immediate (10%)
        if ($minutesInAdvance <= 0) return 0.10;

        if ($minutesInAdvance <= 30)  return 0.10; // 0–30 min
        if ($minutesInAdvance <= 120) return 0.20; // 30 min–2h
        if ($minutesInAdvance <= 360) return 0.35; // 2h–6h
        return 0.50;                               // 6h+
    }

    private function depositLabel(float $rate): string
    {
        return match($rate) {
            0.10 => '10% — arriving within 30 minutes',
            0.20 => '20% — arriving in 30 min to 2 hours',
            0.35 => '35% — arriving in 2 to 6 hours',
            0.50 => '50% — arriving in 6+ hours',
            default => round($rate * 100) . '%',
        };
    }

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
            return Carbon::parse($r->expires_at)->diffInMinutes(now()) < 30
                && Carbon::parse($r->expires_at)->isFuture();
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
            'parking_duration' => 'required|in:1,2,4,8',
            'arrival_time'     => 'required|date|after_or_equal:now',
        ], [
            'arrival_time.after_or_equal' => 'Arrival time must be now or in the future.',
            'arrival_time.required'       => 'Please select your arrival time.',
        ]);

        $parkingHours = (int) $validated['parking_duration'];
        $arrivalTime  = Carbon::parse($validated['arrival_time']);

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
            $depositRate = $this->calculateDepositRate($arrivalTime);

            DB::transaction(function () use ($spotId, $parkingHours, $arrivalTime, $depositRate) {
                $spot = ParkingSpot::lockForUpdate()->findOrFail($spotId);

                if ($spot->status !== 'available') {
                    throw new \Exception('This spot is no longer available.');
                }

                $hourlyRate    = $spot->location->hourly_rate ?? 0;
                $totalCost     = $hourlyRate * $parkingHours;
                $depositAmount = round($totalCost * $depositRate, 2);

                $spot->update(['status' => 'reserved']);

                Reservation::create([
                    'parking_spot_id' => $spot->id,
                    'user_id'         => auth()->id(),
                    'user_name'       => auth()->user()->name,
                    'reserved_at'     => now(),
                    'arrival_time'    => $arrivalTime,
                    'expires_at'      => $arrivalTime->copy()->addHours($parkingHours),
                    'duration_hours'  => $parkingHours,
                    'total_cost'      => $totalCost,
                    'deposit_amount'  => $depositAmount,
                    'deposit_rate'    => $depositRate,
                ]);
            });

            $depositPct   = $depositRate * 100;
            $label        = $this->depositLabel($depositRate);
            return redirect()->back()->with(
                'success',
                "Spot reserved! Arrival: {$arrivalTime->format('d M Y, H:i')} · Parking: {$parkingHours}h · Deposit: {$depositPct}% ({$label})."
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