<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VoyageCraft') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|cormorant-garamond:500,600,700&display=swap" rel="stylesheet" />

        <x-app-assets />
    </head>
    <body class="antialiased">
        @php
            $guestCovers = \App\Models\Itinerary::whereNotNull('cover_image_remote_url')
                ->latest()
                ->take(2)
                ->get();
        @endphp
        <div class="relative min-h-screen overflow-hidden px-4 py-6 sm:px-6 lg:px-10">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[30rem] bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.22),transparent_35%),radial-gradient(circle_at_top_right,rgba(245,158,11,0.16),transparent_28%)]"></div>
            <div class="mx-auto grid min-h-[calc(100vh-3rem)] max-w-6xl gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <section class="surface-panel-dark relative overflow-hidden p-8 sm:p-10 lg:p-12">
                    <div class="absolute inset-0 travel-grid opacity-40"></div>
                    <div class="relative">
                        <a href="/" class="inline-flex items-center gap-3 text-sm font-semibold text-slate-200 transition hover:text-white">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                                <x-application-logo class="h-6 w-6 text-cyan-200" />
                            </span>
                            <span>VoyageCraft</span>
                        </a>

                        <div class="mt-10 max-w-lg">
                            <p class="hero-kicker">Plan with confidence</p>
                            <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Build refined travel plans your guests and teams can trust.</h1>
                            <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">
                                Organize routes, destination timing, exports, and public sharing in one polished itinerary workspace built for modern trips.
                            </p>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">3x</p>
                                <p class="mt-1 text-sm text-slate-300">Faster trip prep with reusable itinerary flows.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">PDF</p>
                                <p class="mt-1 text-sm text-slate-300">Export polished briefs for travelers and partners.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">QR</p>
                                <p class="mt-1 text-sm text-slate-300">Share public views instantly on the go.</p>
                            </div>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-2">
                            <img src="{{ $guestCovers->get(0)?->cover_image_url ?? \App\Models\Itinerary::themeImageUrl('coast') }}" alt="Travel photo preview" class="h-44 w-full rounded-[26px] border border-white/10 object-cover shadow-2xl shadow-cyan-950/30" />
                            <img src="{{ $guestCovers->get(1)?->cover_image_url ?? \App\Models\Itinerary::themeImageUrl('city') }}" alt="Travel photo preview" class="h-44 w-full rounded-[26px] border border-white/10 object-cover shadow-2xl shadow-slate-950/40" />
                        </div>
                    </div>
                </section>

                <section class="surface-panel relative p-6 sm:p-8 lg:p-10">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-teal-700">Member access</p>
                            <h2 class="mt-2 text-3xl font-semibold text-slate-950">Welcome aboard</h2>
                        </div>
                        <a href="/" class="button-ghost">Back home</a>
                    </div>

                    {{ $slot }}
                </section>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
