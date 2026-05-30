<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    public function create($id)
    {
        $location = ParkingLocation::findOrFail($id);
        return view('spots.create', compact('location'));
    }

    public function store(Request $request, $id)
    {
        $location = ParkingLocation::findOrFail($id);

        $validated = $request->validate([
            'spot_number' => 'required|string|max:50',
            'status' => 'required|in:available,occupied,reserved',
            'type' => 'required|in:standard,electric,disabled,garage',
        ]);

        $exists = ParkingSpot::where('parking_location_id', $location->id)
            ->where('spot_number', $validated['spot_number'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Spot number already exists for this location.');
        }

        ParkingSpot::create([
            'parking_location_id' => $location->id,
            'spot_number' => $validated['spot_number'],
            'status' => $validated['status'],
            'type' => $validated['type'],
        ]);

        return redirect('/locations/' . $location->id)->with('success', 'Parking spot added successfully!');
    }

    public function toggle($id)
    {
        $spot = ParkingSpot::findOrFail($id);

        if ($spot->status === 'reserved') {
            return redirect()->back()->with('error', 'Reserved spot cannot be toggled.');
        }

        $spot->status = $spot->status === 'available' ? 'occupied' : 'available';
        $spot->save();

        return redirect()->back()->with('success', 'Spot status updated!');
    }

    public function edit($id)
    {
        $spot = ParkingSpot::findOrFail($id);
        return view('spots.edit', compact('spot'));
    }

    public function update(Request $request, $id)
    {
        $spot = ParkingSpot::findOrFail($id);

        $validated = $request->validate([
            'spot_number' => 'required|string|max:50',
            'type' => 'required|in:standard,electric,disabled,garage',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $exists = ParkingSpot::where('parking_location_id', $spot->parking_location_id)
            ->where('spot_number', $validated['spot_number'])
            ->where('id', '!=', $spot->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Spot number already exists for this location.');
        }

        $spot->update($validated);

        return redirect('/locations/' . $spot->parking_location_id)->with('success', 'Spot updated successfully!');
    }

    public function destroy($id)
    {
        $spot = ParkingSpot::findOrFail($id);
        $locationId = $spot->parking_location_id;

        $spot->delete();

        return redirect('/locations/' . $locationId)->with('success', 'Spot deleted successfully!');
    }

    public function generate($id)
    {
        $location = ParkingLocation::findOrFail($id);

        if ($location->spots()->count() > 0) {
            return redirect()->back()->with('error', 'Spots already exist for this location.');
        }

        $totalSpots = (int) $location->total_spots;

        $letters = range('A', 'Z');
        $generated = 0;
        $rowIndex = 0;
        $number = 1;

        while ($generated < $totalSpots) {
            if (!isset($letters[$rowIndex])) {
                return redirect()->back()->with('error', 'Too many spots to generate with current naming logic.');
            }

            $spotNumber = $letters[$rowIndex] . $number;

            ParkingSpot::create([
                'parking_location_id' => $location->id,
                'spot_number' => $spotNumber,
                'type' => 'standard',
                'status' => 'available',
            ]);

            $generated++;
            $number++;

            if ($number > 20) {
                $number = 1;
                $rowIndex++;
            }
        }

        return redirect()->back()->with('success', 'Spots generated successfully!');
    }
}