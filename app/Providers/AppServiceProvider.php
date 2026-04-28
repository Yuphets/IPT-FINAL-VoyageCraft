<?php

namespace App\Providers;

use App\Models\Itinerary;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\ItineraryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $vercelEnvironment = env('VERCEL_ENV');
        $deploymentHost = match ($vercelEnvironment) {
            'preview' => env('VERCEL_URL'),
            'production' => env('VERCEL_PROJECT_PRODUCTION_URL', env('VERCEL_URL')),
            default => env('VERCEL_URL'),
        };
        $deploymentUrl = $deploymentHost ? 'https://' . $deploymentHost : env('APP_URL');

        if ($deploymentUrl) {
            config(['app.url' => $deploymentUrl]);
            URL::forceRootUrl($deploymentUrl);
        }

        if ($deploymentHost || str_starts_with((string) $deploymentUrl, 'https://')) {
            URL::forceScheme('https');
        }

        // Observer registration (already present)
        User::observe(UserObserver::class);

        // Manual policy registration (only if auto-discovery fails)
        Gate::policy(Itinerary::class, ItineraryPolicy::class);
    }
}
