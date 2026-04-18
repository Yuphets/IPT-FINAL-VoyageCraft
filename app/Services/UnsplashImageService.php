<?php

namespace App\Services;

use App\Models\Itinerary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class UnsplashImageService
{
    public function syncItineraryCoverImage(Itinerary $itinerary, bool $forceRefresh = false): bool
    {
        $itinerary->loadMissing('destinations');

        if ($itinerary->cover_image && !$forceRefresh) {
            return false;
        }

        if (
            !$forceRefresh &&
            $itinerary->cover_image_provider === 'unsplash' &&
            filled($itinerary->cover_image_remote_url)
        ) {
            return false;
        }

        $query = $this->buildItineraryQuery($itinerary);

        if (!$query) {
            return false;
        }

        try {
            $photos = $this->search($query, 1);
            $photo = $photos[0] ?? null;
        } catch (RuntimeException|RequestException) {
            return false;
        }

        if (!$photo) {
            return false;
        }

        $itinerary->forceFill([
            'cover_image' => null,
            'cover_image_provider' => 'unsplash-auto',
            'cover_image_remote_url' => $photo['image_url'],
            'cover_image_author_name' => $photo['photographer_name'],
            'cover_image_author_url' => $photo['photographer_url'],
            'cover_image_source_url' => $photo['source_url'],
        ])->save();

        $this->registerDownload($photo['download_location'] ?? null);

        return true;
    }

    public function search(string $query, int $perPage = 9): array
    {
        $accessKey = config('services.unsplash.access_key');

        if (!$accessKey) {
            throw new RuntimeException('Unsplash is not configured.');
        }

        return Cache::remember(
            'unsplash.search.' . md5(Str::lower($query) . '|' . $perPage),
            now()->addHours(6),
            function () use ($query, $perPage) {
                $response = Http::baseUrl('https://api.unsplash.com')
                    ->acceptJson()
                    ->withOptions([
                        'verify' => config('services.unsplash.verify_ssl', true),
                    ])
                    ->withHeaders([
                        'Accept-Version' => 'v1',
                        'Authorization' => 'Client-ID ' . config('services.unsplash.access_key'),
                    ])
                    ->get('/search/photos', [
                        'query' => $query,
                        'per_page' => min(max($perPage, 1), 30),
                        'orientation' => 'landscape',
                        'content_filter' => 'high',
                    ]);

                if ($response->status() === 403) {
                    throw new RuntimeException('Unsplash rate limit exceeded.');
                }

                $response->throw();

                $utm = http_build_query([
                    'utm_source' => config('services.unsplash.app_name', Str::slug(config('app.name', 'travel-itinerary-app'))),
                    'utm_medium' => 'referral',
                ]);

                return collect($response->json('results', []))
                    ->map(function (array $photo) use ($utm) {
                        $photographerUrl = data_get($photo, 'user.links.html');
                        $photoUrl = data_get($photo, 'links.html');

                        return [
                            'id' => $photo['id'],
                            'description' => $photo['description'] ?: $photo['alt_description'] ?: 'Travel destination photo',
                            'thumbnail_url' => data_get($photo, 'urls.small'),
                            'image_url' => data_get($photo, 'urls.regular'),
                            'photographer_name' => data_get($photo, 'user.name'),
                            'photographer_url' => $photographerUrl ? $photographerUrl . '?' . $utm : null,
                            'source_url' => $photoUrl ? $photoUrl . '?' . $utm : null,
                            'download_location' => data_get($photo, 'links.download_location'),
                        ];
                    })
                    ->filter(fn (array $photo) => $photo['thumbnail_url'] && $photo['image_url'] && $photo['photographer_name'])
                    ->values()
                    ->all();
            }
        );
    }

    public function registerDownload(?string $downloadLocation): void
    {
        $accessKey = config('services.unsplash.access_key');

        if (!$accessKey || !$downloadLocation) {
            return;
        }

        try {
            Http::acceptJson()
                ->withOptions([
                    'verify' => config('services.unsplash.verify_ssl', true),
                ])
                ->withHeaders([
                    'Accept-Version' => 'v1',
                    'Authorization' => 'Client-ID ' . $accessKey,
                ])
                ->get($downloadLocation)
                ->throw();
        } catch (RequestException) {
            // Cover selection should not fail if Unsplash tracking is temporarily unavailable.
        }
    }

    protected function buildItineraryQuery(Itinerary $itinerary): ?string
    {
        $destination = $itinerary->destinations
            ->map(function ($destination) {
                return trim(implode(' ', array_filter([
                    $destination->location,
                    $destination->name,
                ])));
            })
            ->filter()
            ->first();

        $base = $destination ?: $itinerary->title;

        if (!$base && $itinerary->description) {
            $base = Str::words($itinerary->description, 8, '');
        }

        if (!$base) {
            return null;
        }

        return trim($base . ' travel destination');
    }
}
