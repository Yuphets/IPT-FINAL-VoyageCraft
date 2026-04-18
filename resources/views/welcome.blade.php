<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'VoyageCraft') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|cormorant-garamond:500,600,700&display=swap" rel="stylesheet" />
    <x-app-assets />
</head>
<body class="antialiased">
    @php
        $dashboardRoute = auth()->check() && auth()->user()->hasRole('admin')
            ? route('admin.dashboard')
            : route('dashboard');
        $featuredCovers = \App\Models\Itinerary::whereNotNull('cover_image_remote_url')
            ->latest()
            ->take(6)
            ->get();
    @endphp

    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_32%),radial-gradient(circle_at_85%_8%,rgba(245,158,11,0.2),transparent_22%)]"></div>

        <header class="page-wrap pt-6">
            <div class="surface-panel-dark flex flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-6">
                <a href="/" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                        <x-application-logo class="h-6 w-6 text-cyan-200" />
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200/80">Travel workspace</p>
                        <p class="text-lg font-semibold text-white">VoyageCraft</p>
                    </div>
                </a>

                <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                    <a href="#features" class="rounded-full px-4 py-2 text-slate-200 transition hover:bg-white/10 hover:text-white">Features</a>
                    <a href="#gallery" class="rounded-full px-4 py-2 text-slate-200 transition hover:bg-white/10 hover:text-white">Destinations</a>
                    @auth
                        <a href="{{ $dashboardRoute }}" class="rounded-full bg-white px-4 py-2 text-slate-950 transition hover:bg-cyan-100">Open Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-slate-200 transition hover:bg-white/10 hover:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-white px-4 py-2 text-slate-950 transition hover:bg-cyan-100">Create account</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="page-wrap pb-16 pt-10 sm:pb-24 sm:pt-14">
            <section class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <div class="surface-panel-dark relative overflow-hidden p-8 sm:p-10 lg:p-12">
                    <div class="absolute inset-0 travel-grid opacity-30"></div>
                    <div class="relative">
                        <p class="hero-kicker">Professional trip planning</p>
                        <h1 class="mt-5 text-5xl font-semibold leading-none text-white sm:text-6xl lg:text-7xl">
                            Present every journey like a premium travel brief.
                        </h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                            Design itinerary timelines, destination stops, shareable links, QR access, and export-ready PDFs in a travel app that feels ready for clients and real-world adventures.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            @auth
                                <a href="{{ $dashboardRoute }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">Continue planning</a>
                            @else
                                <a href="{{ route('register') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">Start your first itinerary</a>
                                <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Sign in</a>
                            @endauth
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-3xl font-semibold text-white">100%</p>
                                <p class="mt-1 text-sm text-slate-300">Custom trip plans with private or public sharing.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-3xl font-semibold text-white">PDF</p>
                                <p class="mt-1 text-sm text-slate-300">Instant exports for clients, teammates, or personal use.</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-3xl font-semibold text-white">QR</p>
                                <p class="mt-1 text-sm text-slate-300">Hand travelers a live itinerary view in seconds.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <article class="surface-panel overflow-hidden sm:col-span-2">
                        <img src="{{ $featuredCovers->first()?->cover_image_url ?? asset('images/destinations/voyage.svg') }}" alt="Travel planning visual" class="h-72 w-full object-cover sm:h-80" />
                        <div class="grid gap-4 p-6 sm:grid-cols-[1.2fr_0.8fr] sm:items-center">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-teal-700">Trip preview</p>
                                <h2 class="mt-2 text-4xl font-semibold text-slate-950">One place for routes, timing, and presentation.</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    Keep every destination, arrival window, and share option inside a polished itinerary layout that is easy to scan on mobile or desktop.
                                </p>
                            </div>
                            <div class="rounded-[24px] bg-slate-950 p-5 text-slate-100 shadow-xl">
                                <p class="text-sm font-semibold text-cyan-200">Sample trip</p>
                                <p class="mt-3 text-2xl font-semibold">Tokyo Design Week</p>
                                <div class="mt-4 space-y-3 text-sm text-slate-300">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Asakusa arrival and food tour</div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Shibuya gallery and evening city lights</div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">Public link, QR access, and PDF export ready</div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section id="features" class="mt-12 grid gap-5 md:grid-cols-3">
                <article class="metric-card">
                    <p class="soft-badge">Plan</p>
                    <h3 class="mt-4 text-3xl font-semibold text-slate-950">Organized schedules</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Build clear day-by-day destination flows with arrival and departure timing that stays readable under pressure.</p>
                </article>
                <article class="metric-card">
                    <p class="soft-badge">Present</p>
                    <h3 class="mt-4 text-3xl font-semibold text-slate-950">Elegant outputs</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Make each itinerary feel client-ready with polished covers, public pages, QR sharing, and downloadable PDFs.</p>
                </article>
                <article class="metric-card">
                    <p class="soft-badge">Manage</p>
                    <h3 class="mt-4 text-3xl font-semibold text-slate-950">Role-aware admin tools</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Track users, review recent activity, and keep the travel workspace professional as the app grows.</p>
                </article>
            </section>

            <section id="gallery" class="mt-12 surface-panel p-6 sm:p-8">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="soft-badge">Destination art</p>
                        <h2 class="mt-4 section-title">Smart visual themes for the trips your users create.</h2>
                    </div>
                    <p class="max-w-xl text-sm leading-7 text-slate-600">
                        Each itinerary can now fall back to a destination-themed visual, so city breaks, coastal escapes, mountain routes, and cultural adventures all feel intentional even before an upload.
                    </p>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($featuredCovers as $cover)
                        <img src="{{ $cover->cover_image_url }}" alt="{{ $cover->title }}" class="h-60 w-full rounded-[24px] object-cover" />
                    @empty
                        <img src="{{ asset('images/destinations/city.svg') }}" alt="City trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                        <img src="{{ asset('images/destinations/coast.svg') }}" alt="Coastal trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                        <img src="{{ asset('images/destinations/mountain.svg') }}" alt="Mountain trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                        <img src="{{ asset('images/destinations/heritage.svg') }}" alt="Cultural trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                        <img src="{{ asset('images/destinations/adventure.svg') }}" alt="Adventure trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                        <img src="{{ asset('images/destinations/culinary.svg') }}" alt="Food trip illustration" class="h-60 w-full rounded-[24px] object-cover" />
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</body>
</html>
