<?php
namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Models\ParkingLocation;

class FavoriteController extends Controller {
    public function index() {
        $favorites = Favorite::with(['location.spots'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('favorites.index', compact('favorites'));
    }

    public function toggle($locationId) {
        ParkingLocation::findOrFail($locationId);
        $existing = Favorite::where('user_id', auth()->id())
            ->where('parking_location_id', $locationId)
            ->first();

        if ($existing) {
            $existing->delete();
            return redirect()->back()->with('success', 'Removed from favourites.');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'parking_location_id' => $locationId,
        ]);

        return redirect()->back()->with('success', 'Added to favourites!');
    }
}