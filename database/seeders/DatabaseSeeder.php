<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Itinerary;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure roles exist
        $this->createRoles();

        // 2. Create admin user
        $admin = $this->createAdmin();

        // 3. Create 10 regular users with sample itineraries
        $this->createRegularUsers(10);

        $this->command->info('Seeding completed successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Regular users: user1@example.com through user10@example.com (password: password)');
    }

    private function createRoles(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }

    private function createAdmin(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');
        return $admin;
    }

    private function createRegularUsers(int $count): void
    {
        // Create users with predictable emails (user1@example.com, etc.)
        for ($i = 1; $i <= $count; $i++) {
            $user = User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => fake()->name(),
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('user');

            // Create 1-3 itineraries for each user
            $itineraryCount = rand(1, 3);
            for ($j = 0; $j < $itineraryCount; $j++) {
                $itinerary = $this->createItinerary($user);
                // Add 2-5 destinations to each itinerary
                $this->createDestinations($itinerary, rand(2, 5));
            }
        }
    }

    private function createItinerary(User $user): Itinerary
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+2 months');
        $endDate = (clone $startDate)->modify('+' . rand(3, 14) . ' days');

        // Use real travel-related titles for realism
        $titles = [
            'Summer Escape to Bali',
            'European Backpacking Adventure',
            'Tokyo Cultural Immersion',
            'Weekend Getaway in the Mountains',
            'Beach Hopping in Thailand',
            'New York City Exploration',
            'Paris Romantic Retreat',
            'Safari in Kenya',
            'Road Trip Across the American Southwest',
            'Ski Trip to the Swiss Alps',
            'Exploring Ancient Rome',
            'Island Hopping in Greece',
        ];

        return Itinerary::create([
            'user_id' => $user->id,
            'title' => fake()->randomElement($titles) . ' ' . fake()->year(),
            'description' => fake()->optional(0.8)->paragraph(3),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'cover_image' => null, // No real images for seeds
            'is_public' => fake()->boolean(70), // 70% public
        ]);
    }

    private function createDestinations(Itinerary $itinerary, int $count): void
    {
        $destinations = [
            ['Eiffel Tower', 'Paris, France'],
            ['Colosseum', 'Rome, Italy'],
            ['Sagrada Familia', 'Barcelona, Spain'],
            ['Central Park', 'New York, USA'],
            ['Golden Gate Bridge', 'San Francisco, USA'],
            ['Great Wall', 'Beijing, China'],
            ['Machu Picchu', 'Cusco, Peru'],
            ['Taj Mahal', 'Agra, India'],
            ['Sydney Opera House', 'Sydney, Australia'],
            ['Burj Khalifa', 'Dubai, UAE'],
            ['Santorini', 'Greece'],
            ['Banff National Park', 'Canada'],
            ['Christ the Redeemer', 'Rio de Janeiro, Brazil'],
            ['Mount Fuji', 'Japan'],
            ['Angkor Wat', 'Siem Reap, Cambodia'],
        ];

        // Base date from itinerary start
        $currentDate = clone $itinerary->start_date;

        for ($i = 0; $i < $count; $i++) {
            $destination = fake()->randomElement($destinations);

            // Arrival time: morning of the current day
            $arrival = (clone $currentDate)->setTime(rand(8, 11), rand(0, 59));
            // Departure time: afternoon/evening of same day or next day
            $departure = (clone $arrival)->modify('+' . rand(4, 8) . ' hours');

            Destination::create([
                'itinerary_id' => $itinerary->id,
                'name' => $destination[0],
                'description' => fake()->optional(0.7)->sentence(10),
                'arrival_time' => $arrival,
                'departure_time' => $departure,
                'location' => $destination[1],
                'order' => $i,
            ]);

            // Move to next day for next destination (if not last)
            if ($i < $count - 1) {
                $currentDate->modify('+1 day');
            }
        }
    }
}
