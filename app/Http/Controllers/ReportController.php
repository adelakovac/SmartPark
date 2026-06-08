<?php
namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ReportController extends Controller {
    public function store(Request $request, $spotId) {
        ParkingSpot::findOrFail($spotId);
        $validated = $request->validate([
            'type'    => 'required|in:damaged,occupied_wrongly,missing_sign,other',
            'message' => 'nullable|string|max:500',
        ]);

        Report::create([
            'user_id'         => auth()->id(),
            'parking_spot_id' => $spotId,
            'type'            => $validated['type'],
            'message'         => $validated['message'] ?? null,
            'status'          => 'open',
        ]);

        return redirect()->back()->with('success', 'Report submitted. Thank you!');
    }

    public function adminIndex() {
        $reports       = Report::with(['spot.location','user'])->latest()->get();
        $openCount     = $reports->where('status','open')->count();
        $resolvedCount = $reports->where('status','resolved')->count();
        return view('admin.reports', compact('reports','openCount','resolvedCount'));
    }

    public function resolve($id) {
        Report::findOrFail($id)->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Report marked as resolved.');
    }

    public function destroy($id) {
        Report::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Report deleted.');
    }
}