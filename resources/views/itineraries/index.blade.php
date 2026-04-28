<x-app-layout>
    @php
        $emptyStateCover = \App\Models\Itinerary::whereNotNull('cover_image_remote_url')->latest()->first();
    @endphp
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <p class="hero-kicker">Your trip portfolio</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Design memorable itineraries with a cleaner, client-ready finish.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Review upcoming travel plans, open detailed timelines, and create new journeys with covers, QR sharing, and export-ready layouts.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('itineraries.create') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">
                            Create itinerary
                        </a>
                        <a href="{{ route('itineraries.popular') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Explore trending trips
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Trips</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ number_format($itineraries->total()) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Total saved itineraries in your workspace.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Stops</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ number_format($itineraries->sum('destinations_count')) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Destination stops visible in this view.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Sharing</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ $itineraries->where('is_public', true)->count() }}</p>
                        <p class="mt-2 text-sm text-slate-300">Public itineraries on the current page.</p>
                    </div>
                </div>
            </div>
        </section>
    </x-slot>

    <div class="space-y-8 pb-12">
        @if($itineraries->count() > 0)
            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($itineraries as $itinerary)
                    <article class="surface-panel overflow-hidden">
                        <div class="relative">
                            <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="h-64 w-full object-cover" />
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent p-5 text-white">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full {{ $itinerary->is_public ? 'bg-teal-500/20 text-teal-100' : 'bg-slate-900/60 text-slate-100' }} px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">
                                        {{ $itinerary->is_public ? 'Public' : 'Private' }}
                                    </span>
                                    <span class="text-xs font-medium uppercase tracking-[0.18em] text-slate-200">
                                        {{ $itinerary->start_date->format('M d') }} - {{ $itinerary->end_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-3xl font-semibold text-slate-950">{{ $itinerary->title }}</h2>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        {{ $itinerary->description ? \Illuminate\Support\Str::limit($itinerary->description, 110) : 'A clean itinerary ready for destinations, timelines, sharing, and export.' }}
                                    </p>
                                    <x-itinerary-cover-attribution :itinerary="$itinerary" class="mt-3" />
                                </div>
                                <span class="rounded-2xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700">
                                    {{ $itinerary->destinations_count }} stops
                                </span>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('itineraries.show', $itinerary) }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">
                                    Open itinerary
                                </a>
                                <a href="{{ route('itineraries.qr', $itinerary) }}" class="button-ghost">
                                    QR
                                </a>
                                <a href="{{ route('itineraries.pdf', $itinerary) }}" class="button-ghost">
                                    PDF
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="surface-panel p-4">
                {{ $itineraries->links() }}
            </div>
        @else
            <section class="surface-panel overflow-hidden">
                <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr]">
                    <img src="{{ $emptyStateCover?->cover_image_url ?? \App\Models\Itinerary::themeImageUrl('voyage') }}" alt="Travel planning visual" class="h-full min-h-[280px] w-full object-cover" />
                    <div class="p-8 sm:p-10">
                        <p class="soft-badge">No itineraries yet</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950">Start with a trip worth presenting beautifully.</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-600">
                            Create your first itinerary to organize dates, add destination stops, and unlock public sharing, QR codes, and travel-friendly PDF exports.
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('itineraries.create') }}" class="rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                                Create your first itinerary
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
