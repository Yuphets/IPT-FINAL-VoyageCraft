<x-app-layout>
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="hero-kicker">Popular this month</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">See which public trips are getting the most attention.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Browse high-interest itineraries and use them as inspiration for your next travel plan.
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Back to my trips
                </a>
            </div>
        </section>
    </x-slot>

    <div class="grid gap-6 pb-12 md:grid-cols-2 xl:grid-cols-3">
        @forelse($popularItineraries as $itinerary)
            <article class="surface-panel overflow-hidden">
                <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="h-64 w-full object-cover" />
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">{{ $itinerary->user->name }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-950">{{ $itinerary->title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $itinerary->destinations_count }} destinations across a public itinerary.</p>
                    <x-itinerary-cover-attribution :itinerary="$itinerary" class="mt-3" />
                    <a href="{{ route('itineraries.show.public', $itinerary) }}" class="mt-6 inline-flex rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">
                        View public itinerary
                    </a>
                </div>
            </article>
        @empty
            <section class="surface-panel p-8 text-center md:col-span-2 xl:col-span-3">
                <h2 class="text-4xl font-semibold text-slate-950">No public itineraries this month yet.</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Once travelers publish trips with destinations, the most active ones will appear here.</p>
            </section>
        @endforelse
    </div>
</x-app-layout>
