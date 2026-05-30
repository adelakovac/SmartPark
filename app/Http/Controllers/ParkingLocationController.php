<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use Illuminate\Http\Request;

class ParkingLocationController extends Controller
{
    public function index()
    {
        $locations = ParkingLocation::latest()->get();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_spots' => 'required|integer|min:1|max:1000',
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

        $spots = $spotsQuery->paginate(12)->withQueryString();

        $stats = [
            'total' => $location->spots()->count(),
            'available' => $location->spots()->where('status', 'available')->count(),
            'occupied' => $location->spots()->where('status', 'occupied')->count(),
            'reserved' => $location->spots()->where('status', 'reserved')->count(),
        ];

        return view('locations.show', compact('location', 'spots', 'stats'));
    }

   public function dashboard()
{
    $totalLocations = \App\Models\ParkingLocation::count();
    $totalGeneratedSpots = \App\Models\ParkingSpot::count();
    $totalPlannedCapacity = \App\Models\ParkingLocation::sum('total_spots');
    $availableSpots = \App\Models\ParkingSpot::where('status', 'available')->count();
    $occupiedSpots = \App\Models\ParkingSpot::where('status', 'occupied')->count();
    $reservedSpots = \App\Models\ParkingSpot::where('status', 'reserved')->count();

    $utilization = $totalGeneratedSpots > 0
        ? round((($reservedSpots + $occupiedSpots) / $totalGeneratedSpots) * 100)
        : 0;

    $emptyLocations = \App\Models\ParkingLocation::doesntHave('spots')->count();

    $topLocations = \App\Models\ParkingLocation::orderByDesc('total_spots')
        ->take(5)
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
        'topLocations'
    ));
}
}
