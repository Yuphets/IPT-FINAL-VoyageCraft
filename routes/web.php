<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlaceImageController;
use App\Models\Itinerary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Throwable;

Route::get('/', function () {
    $featuredCovers = collect();

    try {
        $featuredCovers = Itinerary::whereNotNull('cover_image_remote_url')
            ->latest()
            ->take(6)
            ->get();
    } catch (Throwable) {
        $featuredCovers = new Collection();
    }

    return view('welcome', [
        'featuredCovers' => $featuredCovers,
    ]);
});

Route::get('/dashboard', [ItineraryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('api/place-images/search', [PlaceImageController::class, 'search'])->name('place-images.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Itineraries
    Route::resource('itineraries', ItineraryController::class);
    Route::get('itineraries/{itinerary}/pdf', [ItineraryController::class, 'downloadPDF'])->name('itineraries.pdf');
    Route::get('itineraries/{itinerary}/qr', [ItineraryController::class, 'showQrCode'])->name('itineraries.qr');
    Route::get('popular', [ItineraryController::class, 'popular'])->name('itineraries.popular');

    // Destinations nested
    Route::post('itineraries/{itinerary}/destinations', [DestinationController::class, 'store'])->name('destinations.store');
    Route::delete('itineraries/{itinerary}/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/report', [AdminController::class, 'generateReport'])->name('admin.report');
    });
});

// Public itinerary view (for QR sharing)
Route::get('public/itineraries/{itinerary}', [ItineraryController::class, 'publicShow'])
    ->name('itineraries.show.public');

require __DIR__.'/auth.php';
