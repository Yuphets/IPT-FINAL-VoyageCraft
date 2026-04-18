<?php

namespace App\Providers;

use App\Models\Itinerary;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\ItineraryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Observer registration (already present)
        User::observe(UserObserver::class);

        // Manual policy registration (only if auto-discovery fails)
        Gate::policy(Itinerary::class, ItineraryPolicy::class);
    }
}
