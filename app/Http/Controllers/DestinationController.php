<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Destination;
use App\Services\UnsplashImageService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function store(Request $request, Itinerary $itinerary, UnsplashImageService $unsplash)
    {
        $this->authorize('update', $itinerary);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'arrival_time' => 'required|date',
            'departure_time' => 'required|date|after:arrival_time',
            'location' => 'nullable|string',
            'order' => 'integer',
        ]);

        $validated['itinerary_id'] = $itinerary->id;
        $validated['order'] = $validated['order'] ?? (($itinerary->destinations()->max('order') ?? -1) + 1);

        Destination::create($validated);

        if (!$itinerary->cover_image && $itinerary->cover_image_provider !== 'unsplash') {
            $unsplash->syncItineraryCoverImage($itinerary->fresh('destinations'), true);
        }

        return back()->with('success', 'Destination added.');
    }

    public function destroy(Itinerary $itinerary, Destination $destination, UnsplashImageService $unsplash)
    {
        $this->authorize('update', $itinerary);

        abort_unless($destination->itinerary_id === $itinerary->id, 404);

        $destination->delete();

        if (!$itinerary->cover_image && $itinerary->cover_image_provider !== 'unsplash') {
            $unsplash->syncItineraryCoverImage($itinerary->fresh('destinations'), true);
        }

        return back()->with('success', 'Destination removed.');
    }
}
