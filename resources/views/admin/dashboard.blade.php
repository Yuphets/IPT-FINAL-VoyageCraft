<x-app-layout>
    @php
        $adminCover = \App\Models\Itinerary::whereNotNull('cover_image_remote_url')->latest()->first();
    @endphp
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <p class="hero-kicker">Admin control room</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Keep the travel platform organized, visible, and ready to scale.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Review the health of the app, monitor recent itinerary activity, and manage users from a dashboard that feels more like a product console than a default scaffold.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('admin.users') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">Manage users</a>
                        <a href="{{ route('admin.report') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Download report</a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Users</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ number_format($totalUsers) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Registered travelers and planners.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Itineraries</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ number_format($totalItineraries) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Total travel plans in the system.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-200/80">Public</p>
                        <p class="mt-3 text-4xl font-semibold text-white">{{ number_format($publicItineraries) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Shareable itineraries currently live.</p>
                    </div>
                </div>
            </div>
        </section>
    </x-slot>

    <div class="grid gap-8 pb-12 xl:grid-cols-[1fr_0.34fr]">
        <section class="surface-panel overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                <p class="soft-badge">Recent activity</p>
                <h2 class="mt-4 text-4xl font-semibold text-slate-950">Latest itineraries</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4 sm:px-8">Trip</th>
                            <th class="px-6 py-4">Owner</th>
                            <th class="px-6 py-4">Dates</th>
                            <th class="px-6 py-4">Stops</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white text-sm text-slate-600">
                        @forelse($recentItineraries as $itinerary)
                            <tr>
                                <td class="px-6 py-5 align-top sm:px-8">
                                    <a href="{{ route('itineraries.show', $itinerary) }}" class="font-semibold text-slate-900 transition hover:text-teal-700">
                                        {{ \Illuminate\Support\Str::limit($itinerary->title, 42) }}
                                    </a>
                                </td>
                                <td class="px-6 py-5 align-top">{{ $itinerary->user->name }}</td>
                                <td class="px-6 py-5 align-top">{{ $itinerary->start_date->format('M d') }} to {{ $itinerary->end_date->format('M d, Y') }}</td>
                                <td class="px-6 py-5 align-top">{{ $itinerary->destinations_count }}</td>
                                <td class="px-6 py-5 align-top">
                                    <span class="rounded-full {{ $itinerary->is_public ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-700' }} px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em]">
                                        {{ $itinerary->is_public ? 'Public' : 'Private' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">{{ $itinerary->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">No itineraries found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="surface-panel p-6">
                <p class="soft-badge">Quick actions</p>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('admin.users') }}" class="block rounded-[24px] bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-800 transition hover:bg-slate-100">Open user management</a>
                    <a href="{{ route('admin.report') }}" class="block rounded-[24px] bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-800 transition hover:bg-slate-100">Generate platform report</a>
                    <a href="{{ route('dashboard') }}" class="block rounded-[24px] bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-800 transition hover:bg-slate-100">Switch to traveler dashboard</a>
                </div>
            </section>

            <section class="surface-panel overflow-hidden">
                <img src="{{ $adminCover?->cover_image_url ?? \App\Models\Itinerary::themeImageUrl('city') }}" alt="Admin dashboard visual" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-3xl font-semibold text-slate-950">Clean oversight</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Public itinerary counts and recent activity are now easier to review without leaving the dashboard.
                    </p>
                </div>
            </section>
        </aside>
    </div>
</x-app-layout>
