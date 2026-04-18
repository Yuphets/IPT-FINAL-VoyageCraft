<?php

use App\Models\Itinerary;
use App\Services\UnsplashImageService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('itineraries:sync-cover-images', function (UnsplashImageService $unsplash) {
    $synced = 0;

    Itinerary::with('destinations')
        ->orderBy('id')
        ->chunkById(50, function ($itineraries) use ($unsplash, &$synced) {
            foreach ($itineraries as $itinerary) {
                if ($unsplash->syncItineraryCoverImage($itinerary)) {
                    $synced++;
                    $this->line("Synced itinerary #{$itinerary->id}: {$itinerary->title}");
                }
            }
        });

    $this->info("Finished syncing cover images. Updated {$synced} itineraries.");
})->purpose('Backfill real Unsplash cover images for itineraries');
