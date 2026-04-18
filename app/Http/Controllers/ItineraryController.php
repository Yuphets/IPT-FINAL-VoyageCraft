<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Destination;
use App\Services\UnsplashImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItineraryController extends Controller
{
    protected function itineraryRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cover_image' => 'nullable|image|max:2048',
            'selected_cover_image_provider' => 'nullable|required_with:selected_cover_image_url|string|in:unsplash',
            'selected_cover_image_url' => 'nullable|url|max:2048',
            'selected_cover_image_author_name' => 'nullable|required_with:selected_cover_image_url|string|max:255',
            'selected_cover_image_author_url' => 'nullable|required_with:selected_cover_image_url|url|max:2048',
            'selected_cover_image_source_url' => 'nullable|required_with:selected_cover_image_url|url|max:2048',
            'selected_cover_image_download_location' => 'nullable|url|max:2048',
            'is_public' => 'boolean',
        ];
    }

    public function index()
    {
        $itineraries = Auth::user()->itineraries()
            ->withCount('destinations')
            ->latest()
            ->paginate(9);

        return view('itineraries.index', compact('itineraries'));
    }

    public function create()
    {
        return view('itineraries.create');
    }

    public function store(Request $request)
    {
        $hasUploadedCover = $request->hasFile('cover_image');
        $hasSelectedRemoteCover = filled($request->input('selected_cover_image_url'));
        $validated = $request->validate($this->itineraryRules());

        $validated['user_id'] = Auth::id();

        $validated = $this->applyCoverImageSelection($request, $validated, null, app(UnsplashImageService::class));

        $itinerary = Itinerary::create($validated);

        if (!$hasUploadedCover && !$hasSelectedRemoteCover) {
            app(UnsplashImageService::class)->syncItineraryCoverImage($itinerary);
        }

        return redirect()->route('itineraries.show', $itinerary)
            ->with('success', 'Itinerary created successfully.');
    }

    public function show(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);
        $itinerary->load('destinations');
        $destinations = $itinerary->destinations;

        return view('itineraries.show', compact('itinerary', 'destinations'));
    }

    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);
        return view('itineraries.edit', compact('itinerary'));
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $hasUploadedCover = $request->hasFile('cover_image');
        $hasSelectedRemoteCover = filled($request->input('selected_cover_image_url'));
        $validated = $request->validate($this->itineraryRules());

        $validated = $this->applyCoverImageSelection($request, $validated, $itinerary, app(UnsplashImageService::class));

        $itinerary->update($validated);

        if (
            !$hasUploadedCover &&
            !$hasSelectedRemoteCover &&
            (!$itinerary->cover_image || $itinerary->cover_image_provider === 'unsplash-auto' || blank($itinerary->cover_image_remote_url))
        ) {
            app(UnsplashImageService::class)->syncItineraryCoverImage($itinerary, true);
        }

        return redirect()->route('itineraries.show', $itinerary)
            ->with('success', 'Itinerary updated.');
    }

    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);

        if ($itinerary->cover_image) {
            Storage::disk('public')->delete($itinerary->cover_image);
        }

        $itinerary->delete();
        return redirect()->route('dashboard')->with('success', 'Itinerary deleted.');
    }

    // PDF Generation
    public function downloadPDF(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        $pdf = Pdf::loadView('pdf.itinerary', compact('itinerary'));
        return $pdf->download('itinerary-' . $itinerary->id . '.pdf');
    }

    // QR Code Generation
    public function showQrCode(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        $url = route('itineraries.show.public', $itinerary->id);
        $qrCode = QrCode::size(300)->generate($url);

        return view('itineraries.qr-code', compact('itinerary', 'qrCode'));
    }

    // Public view (for QR code link)
    public function publicShow(Itinerary $itinerary)
    {
        if (!$itinerary->is_public) {
            abort(404);
        }

        $itinerary->loadMissing('user', 'destinations');

        return view('itineraries.public-show', compact('itinerary'));
    }

    // Complex Query: Popular Itineraries
    public function popular()
    {
        $popularItineraries = Itinerary::where('is_public', true)
            ->where('created_at', '>=', now()->subMonth())
            ->with('user')
            ->withCount('destinations')
            ->orderBy('destinations_count', 'desc')
            ->limit(10)
            ->get();

        return view('itineraries.popular', compact('popularItineraries'));
    }

    protected function applyCoverImageSelection(
        Request $request,
        array $validated,
        ?Itinerary $itinerary,
        UnsplashImageService $unsplash,
    ): array {
        $selectedImageUrl = $validated['selected_cover_image_url'] ?? null;

        if ($request->hasFile('cover_image')) {
            if ($itinerary?->cover_image) {
                Storage::disk('public')->delete($itinerary->cover_image);
            }

            $image = $request->file('cover_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('itinerary_covers', $filename, 'public');

            $img = Image::make($image->getRealPath());
            $img->fit(800, 500)->save(storage_path('app/public/' . $path));

            $validated['cover_image'] = $path;
            $validated['cover_image_provider'] = null;
            $validated['cover_image_remote_url'] = null;
            $validated['cover_image_author_name'] = null;
            $validated['cover_image_author_url'] = null;
            $validated['cover_image_source_url'] = null;

            return $this->stripTransientCoverFields($validated);
        }

        if ($selectedImageUrl) {
            if ($itinerary?->cover_image) {
                Storage::disk('public')->delete($itinerary->cover_image);
            }

            $validated['cover_image'] = null;
            $validated['cover_image_provider'] = $validated['selected_cover_image_provider'];
            $validated['cover_image_remote_url'] = $selectedImageUrl;
            $validated['cover_image_author_name'] = $validated['selected_cover_image_author_name'];
            $validated['cover_image_author_url'] = $validated['selected_cover_image_author_url'];
            $validated['cover_image_source_url'] = $validated['selected_cover_image_source_url'];

            $unsplash->registerDownload($validated['selected_cover_image_download_location'] ?? null);

            return $this->stripTransientCoverFields($validated);
        }

        return $this->stripTransientCoverFields($validated);
    }

    protected function stripTransientCoverFields(array $validated): array
    {
        unset(
            $validated['selected_cover_image_provider'],
            $validated['selected_cover_image_url'],
            $validated['selected_cover_image_author_name'],
            $validated['selected_cover_image_author_url'],
            $validated['selected_cover_image_source_url'],
            $validated['selected_cover_image_download_location'],
        );

        return $validated;
    }
}
