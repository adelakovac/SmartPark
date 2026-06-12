<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ParkingLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = ParkingLocation::with('spots')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $locations = $query->get();
        $cities = ParkingLocation::select('city')->distinct()->orderBy('city')->pluck('city');

        return view('locations.index', compact('locations', 'cities'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'total_spots'   => 'required|integer|min:1|max:1000',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'hourly_rate'   => 'nullable|numeric|min:0',
            'opening_hours' => ['nullable', 'regex:/^([01]?[0-9]|2[0-4]):[0-5][0-9] - ([01]?[0-9]|2[0-4]):[0-5][0-9]$/', 'max:100'],
        ]);

        ParkingLocation::create($validated);

        return redirect()->route('locations.index')->with('success', 'Location created successfully!');
    }

    public function show(Request $request, $id)
    {
        $location = ParkingLocation::findOrFail($id);

        $spotsQuery = $location->spots()->orderBy('spot_number');

        if ($request->filled('search')) {
            $spotsQuery->where('spot_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $spotsQuery->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $spotsQuery->where('type', $request->type);
        }

        $spots = $spotsQuery->paginate(20)->withQueryString();

        $stats = [
            'total'     => $location->spots()->count(),
            'available' => $location->spots()->where('status', 'available')->count(),
            'occupied'  => $location->spots()->where('status', 'occupied')->count(),
            'reserved'  => $location->spots()->where('status', 'reserved')->count(),
        ];

        return view('locations.show', compact('location', 'spots', 'stats'));
    }

    public function edit($id)
    {
        $location = ParkingLocation::findOrFail($id);
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $location = ParkingLocation::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'total_spots'   => 'required|integer|min:1|max:1000',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'hourly_rate'   => 'nullable|numeric|min:0',
            'opening_hours' => ['nullable', 'regex:/^([01]?[0-9]|2[0-4]):[0-5][0-9] - ([01]?[0-9]|2[0-4]):[0-5][0-9]$/', 'max:100'],
        ]);

        $location->update($validated);

        return redirect()->route('locations.show', $location->id)
            ->with('success', 'Location updated successfully!');
    }

    public function destroy($id)
    {
        $location = ParkingLocation::findOrFail($id);
        $name = $location->name;
        $location->delete(); // cascades to spots and reservations
        return redirect()->route('locations.index')
            ->with('success', "Location \"{$name}\" and all its spots have been deleted.");
    }

    public function map()
    {
        $locations = ParkingLocation::with('spots')->get()->map(function ($loc) {
            $total     = $loc->spots->count();
            $available = $loc->spots->where('status', 'available')->count();
            $reserved  = $loc->spots->where('status', 'reserved')->count();
            $occupied  = $loc->spots->where('status', 'occupied')->count();

            return [
                'id'            => $loc->id,
                'name'          => $loc->name,
                'address'       => $loc->address,
                'city'          => $loc->city,
                'description'   => $loc->description,
                'total_spots'   => $loc->total_spots,
                'hourly_rate'   => $loc->hourly_rate ?? 0,
                'opening_hours' => $loc->opening_hours ?? '00:00 - 24:00',
                'latitude'      => $loc->latitude,
                'longitude'     => $loc->longitude,
                'stats' => [
                    'total'     => $total,
                    'available' => $available,
                    'reserved'  => $reserved,
                    'occupied'  => $occupied,
                ],
                'url' => route('locations.show', $loc->id),
            ];
        });

        return view('map', compact('locations'));
    }

    public function dashboard()
    {
        $totalLocations       = ParkingLocation::count();
        $totalGeneratedSpots  = ParkingSpot::count();
        $totalPlannedCapacity = ParkingLocation::sum('total_spots');
        $availableSpots       = ParkingSpot::where('status', 'available')->count();
        $occupiedSpots        = ParkingSpot::where('status', 'occupied')->count();
        $reservedSpots        = ParkingSpot::where('status', 'reserved')->count();

        $utilization = $totalGeneratedSpots > 0
            ? round((($reservedSpots + $occupiedSpots) / $totalGeneratedSpots) * 100)
            : 0;

        $emptyLocations = ParkingLocation::doesntHave('spots')->count();

        $topLocations = ParkingLocation::withCount([
            'spots',
            'spots as available_spots_count' => fn($q) => $q->where('status', 'available'),
        ])->orderByDesc('total_spots')->take(5)->get();

        $myReservations = Reservation::with(['spot.location'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalLocations',
            'totalGeneratedSpots',
            'totalPlannedCapacity',
            'availableSpots',
            'occupiedSpots',
            'reservedSpots',
            'utilization',
            'emptyLocations',
            'topLocations',
            'myReservations'
        ));
    }
}