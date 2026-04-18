<?php

namespace Tests\Feature;

use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ItineraryCoverImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_itinerary_with_unsplash_cover_photo(): void
    {
        config()->set('services.unsplash.access_key', 'test-unsplash-key');

        Http::fake([
            'https://api.unsplash.com/photos/*/download*' => Http::response([
                'url' => 'https://images.example.com/downloaded.jpg',
            ]),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('itineraries.store'), [
            'title' => 'Tokyo design week',
            'description' => 'Urban itinerary with real imagery.',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-05',
            'is_public' => '1',
            'selected_cover_image_provider' => 'unsplash',
            'selected_cover_image_url' => 'https://images.example.com/tokyo-regular.jpg',
            'selected_cover_image_author_name' => 'Jane Doe',
            'selected_cover_image_author_url' => 'https://unsplash.com/@janedoe?utm_source=travel-itinerary-app&utm_medium=referral',
            'selected_cover_image_source_url' => 'https://unsplash.com/photos/tokyo-1?utm_source=travel-itinerary-app&utm_medium=referral',
            'selected_cover_image_download_location' => 'https://api.unsplash.com/photos/tokyo-1/download',
        ]);

        $itinerary = Itinerary::firstOrFail();

        $response->assertRedirect(route('itineraries.show', $itinerary));

        $this->assertSame('unsplash', $itinerary->cover_image_provider);
        $this->assertSame('https://images.example.com/tokyo-regular.jpg', $itinerary->cover_image_remote_url);
        $this->assertNull($itinerary->cover_image);
        $this->assertSame('https://images.example.com/tokyo-regular.jpg', $itinerary->cover_image_url);
    }
}
