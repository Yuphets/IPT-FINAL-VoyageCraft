<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $itinerary->title }} | {{ config('app.name', 'VoyageCraft') }}</title>
    <meta property="og:title" content="{{ $itinerary->title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($itinerary->description, 150) }}">
    <meta property="og:image" content="{{ $itinerary->cover_image_url }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|cormorant-garamond:500,600,700&display=swap" rel="stylesheet" />
    <x-app-assets />
</head>
<body class="antialiased">
    <div class="relative min-h-screen overflow-hidden pb-16">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[28rem] bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_32%),radial-gradient(circle_at_top_right,rgba(245,158,11,0.18),transparent_22%)]"></div>

        <header class="page-wrap pt-6">
            <div class="surface-panel-dark flex flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-6">
                <a href="/" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                        <x-application-logo class="h-6 w-6 text-cyan-200" />
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200/80">Shared itinerary</p>
                        <p class="text-lg font-semibold text-white">VoyageCraft</p>
                    </div>
                </a>
                <a href="/" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">Create your own</a>
            </div>
        </header>

        <main class="page-wrap pt-10">
            <section class="surface-panel-dark overflow-hidden">
                <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="p-8 sm:p-10">
                        <p class="hero-kicker">Public itinerary</p>
                        <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">{{ $itinerary->title }}</h1>
                        <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                            {{ $itinerary->description ?: 'A shared trip outline with destination timing and planning details.' }}
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-300">
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">{{ $itinerary->start_date->format('M d, Y') }} to {{ $itinerary->end_date->format('M d, Y') }}</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">{{ $itinerary->destinations->count() }} destinations</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Created by {{ $itinerary->user->name }}</span>
                        </div>
                        <x-itinerary-cover-attribution :itinerary="$itinerary" class="mt-6 text-slate-300 [&>a]:text-cyan-200 [&>a:hover]:text-white" />
                    </div>
                    <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="h-full min-h-[320px] w-full object-cover" />
                </div>
            </section>

            <section class="mt-8 surface-panel p-6 sm:p-8">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="soft-badge">Schedule</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950">Destination timeline</h2>
                    </div>
                    <p class="max-w-xl text-sm leading-7 text-slate-600">A clean public view travelers can scan quickly on mobile or desktop.</p>
                </div>

                @if($itinerary->destinations->count() > 0)
                    <div class="mt-8 space-y-5">
                        @foreach($itinerary->destinations as $index => $destination)
                            <article class="rounded-[28px] border border-slate-200 bg-slate-50/80 p-5 sm:p-6">
                                <div class="flex flex-wrap items-start gap-4">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <h3 class="text-3xl font-semibold text-slate-950">{{ $destination->name }}</h3>
                                        <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-600">
                                            <span class="rounded-full bg-white px-3 py-2">{{ $destination->arrival_time->format('M d, Y g:i A') }}</span>
                                            <span class="rounded-full bg-white px-3 py-2">{{ $destination->departure_time->format('M d, Y g:i A') }}</span>
                                            @if($destination->location)
                                                <span class="rounded-full bg-white px-3 py-2">{{ $destination->location }}</span>
                                            @endif
                                        </div>
                                        @if($destination->description)
                                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $destination->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-8 rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-600">
                        No destinations have been added to this public itinerary yet.
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
