<x-app-layout>
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden">
            <div class="grid gap-0 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="p-8 sm:p-10">
                    <p class="hero-kicker">Itinerary details</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">{{ $itinerary->title }}</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        {{ $itinerary->description ?: 'A polished travel plan with room for schedules, destinations, public sharing, and downloadable handoff materials.' }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('itineraries.edit', $itinerary) }}" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">Edit trip</a>
                        <a href="{{ route('itineraries.pdf', $itinerary) }}" class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Download PDF</a>
                        <a href="{{ route('itineraries.qr', $itinerary) }}" class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Show QR</a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-300">
                        <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">{{ $itinerary->start_date->format('M d, Y') }} to {{ $itinerary->end_date->format('M d, Y') }}</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">{{ $destinations->count() }} destinations</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">{{ $itinerary->is_public ? 'Public sharing enabled' : 'Private itinerary' }}</span>
                    </div>

                    <x-itinerary-cover-attribution :itinerary="$itinerary" class="mt-6 text-slate-300 [&>a]:text-cyan-200 [&>a:hover]:text-white" />
                </div>

                <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="h-full min-h-[320px] w-full object-cover" />
            </div>
        </section>
    </x-slot>

    <div class="grid gap-8 pb-12 xl:grid-cols-[1fr_0.34fr]">
        <section class="space-y-6">
            <div class="surface-panel p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="soft-badge">Destination flow</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950">Every stop, arranged in order.</h2>
                    </div>
                    <button onclick="document.getElementById('add-destination-modal').classList.remove('hidden')" class="rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Add destination
                    </button>
                </div>

                @if($destinations->count() > 0)
                    <div class="mt-8 space-y-5">
                        @foreach($destinations as $index => $destination)
                            <article class="rounded-[28px] border border-slate-200 bg-slate-50/80 p-5 sm:p-6">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <h3 class="text-3xl font-semibold text-slate-950">{{ $destination->name }}</h3>
                                            <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-600">
                                                <span class="rounded-full bg-white px-3 py-2">{{ $destination->arrival_time->format('M d, Y g:i A') }}</span>
                                                <span class="rounded-full bg-white px-3 py-2">{{ $destination->departure_time->format('M d, Y g:i A') }}</span>
                                                @if($destination->location)
                                                    <span class="rounded-full bg-white px-3 py-2">{{ $destination->location }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('destinations.destroy', [$itinerary, $destination]) }}" method="POST" onsubmit="return confirm('Remove this destination?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button-ghost">Remove</button>
                                    </form>
                                </div>

                                @if($destination->description)
                                    <p class="mt-5 text-sm leading-7 text-slate-600">{{ $destination->description }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-8 rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                        <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="mx-auto h-40 w-full max-w-md rounded-[24px] object-cover" />
                        <h3 class="mt-6 text-3xl font-semibold text-slate-950">No destinations added yet</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Add your first stop to start building the travel timeline for this itinerary.</p>
                    </div>
                @endif
            </div>
        </section>

        <aside class="space-y-6">
            <section class="surface-panel p-6">
                <p class="soft-badge">Trip summary</p>
                <div class="mt-5 space-y-4 text-sm text-slate-600">
                    <div class="rounded-[24px] bg-slate-50 px-4 py-4">
                        <p class="font-semibold text-slate-900">Duration</p>
                        <p class="mt-1">{{ $itinerary->start_date->diffInDays($itinerary->end_date) + 1 }} days</p>
                    </div>
                    <div class="rounded-[24px] bg-slate-50 px-4 py-4">
                        <p class="font-semibold text-slate-900">Visibility</p>
                        <p class="mt-1">{{ $itinerary->is_public ? 'Public link enabled' : 'Private access only' }}</p>
                    </div>
                    <div class="rounded-[24px] bg-slate-50 px-4 py-4">
                        <p class="font-semibold text-slate-900">Share tools</p>
                        <p class="mt-1">QR code and PDF export are ready whenever you need them.</p>
                    </div>
                </div>
            </section>

            <section class="surface-panel overflow-hidden">
                <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-3xl font-semibold text-slate-950">Visual identity</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        This itinerary now uses your saved trip cover, whether it came from a real place photo search or a manual upload.
                    </p>
                    <x-itinerary-cover-attribution :itinerary="$itinerary" class="mt-3" />
                </div>
            </section>

            <form action="{{ route('itineraries.destroy', $itinerary) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this itinerary? This cannot be undone.');" class="surface-panel p-6">
                @csrf
                @method('DELETE')
                <h3 class="text-3xl font-semibold text-slate-950">Danger zone</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">Remove this itinerary and its destinations if you no longer need the trip.</p>
                <button type="submit" class="mt-5 rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Delete itinerary
                </button>
            </form>
        </aside>
    </div>

    <div id="add-destination-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('add-destination-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <div class="surface-panel inline-block w-full max-w-xl transform overflow-hidden text-left align-bottom shadow-2xl transition-all sm:my-8 sm:align-middle">
                <form action="{{ route('destinations.store', $itinerary) }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="soft-badge">New stop</p>
                            <h3 class="mt-4 text-4xl font-semibold text-slate-950" id="modal-title">Add destination</h3>
                        </div>
                        <button type="button" onclick="document.getElementById('add-destination-modal').classList.add('hidden')" class="button-ghost">Close</button>
                    </div>

                    <div class="mt-6 space-y-5">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-800">Destination name</label>
                            <input type="text" name="name" id="name" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10" placeholder="Eiffel Tower, Banff, Shibuya Crossing..." />
                        </div>
                        <div>
                            <label for="description" class="mb-2 block text-sm font-semibold text-slate-800">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10"></textarea>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="arrival_time" class="mb-2 block text-sm font-semibold text-slate-800">Arrival</label>
                                <input type="datetime-local" name="arrival_time" id="arrival_time" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10">
                            </div>
                            <div>
                                <label for="departure_time" class="mb-2 block text-sm font-semibold text-slate-800">Departure</label>
                                <input type="datetime-local" name="departure_time" id="departure_time" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10">
                            </div>
                        </div>
                        <div>
                            <label for="location" class="mb-2 block text-sm font-semibold text-slate-800">Location</label>
                            <input type="text" name="location" id="location" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10" placeholder="Address, district, or area" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap justify-end gap-3">
                        <button type="button" onclick="document.getElementById('add-destination-modal').classList.add('hidden')" class="button-ghost">Cancel</button>
                        <button type="submit" class="rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                            Save destination
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
