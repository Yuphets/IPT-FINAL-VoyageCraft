<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PlaceImageSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_search_place_images(): void
    {
        config()->set('services.unsplash.access_key', 'test-unsplash-key');
        config()->set('services.unsplash.app_name', 'travel-itinerary-app');

        Http::fake([
            'https://api.unsplash.com/search/photos*' => Http::response([
                'results' => [
                    [
                        'id' => 'tokyo-1',
                        'description' => 'Tokyo skyline at night',
                        'alt_description' => 'Tokyo skyline',
                        'urls' => [
                            'small' => 'https://images.example.com/tokyo-small.jpg',
                            'regular' => 'https://images.example.com/tokyo-regular.jpg',
                        ],
                        'user' => [
                            'name' => 'Jane Doe',
                            'links' => [
                                'html' => 'https://unsplash.com/@janedoe',
                            ],
                        ],
                        'links' => [
                            'html' => 'https://unsplash.com/photos/tokyo-1',
                            'download_location' => 'https://api.unsplash.com/photos/tokyo-1/download',
                        ],
                    ],
                ],
            ]),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('place-images.search', [
            'query' => 'Tokyo',
        ]));

        $response->assertOk()
            ->assertJsonPath('data.0.id', 'tokyo-1')
            ->assertJsonPath('data.0.image_url', 'https://images.example.com/tokyo-regular.jpg')
            ->assertJsonPath('data.0.photographer_name', 'Jane Doe');
    }

    public function test_place_image_search_returns_service_unavailable_when_unsplash_is_not_configured(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('place-images.search', [
            'query' => 'Paris',
        ]));

        $response->assertStatus(503)
            ->assertJsonPath('message', 'Place photo search is unavailable until Unsplash is configured.');
    }
}
