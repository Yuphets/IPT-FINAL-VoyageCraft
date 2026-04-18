@php
    $isAdmin = auth()->user()->hasRole('admin');
    $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('dashboard');
@endphp

<nav x-data="{ open: false }" class="page-wrap pt-5">
    <div class="surface-panel-dark px-5 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ $dashboardRoute }}" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                        <x-application-logo class="h-6 w-6 text-cyan-200" />
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200/80">Travel workspace</p>
                        <p class="text-lg font-semibold text-white">VoyageCraft</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard', 'itineraries.*') ? 'bg-white text-slate-950' : 'text-slate-200 hover:bg-white/10 hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                        My Trips
                    </a>
                    <a href="{{ route('itineraries.popular') }}" class="{{ request()->routeIs('itineraries.popular') ? 'bg-white text-slate-950' : 'text-slate-200 hover:bg-white/10 hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                        Trending
                    </a>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'bg-white text-slate-950' : 'text-slate-200 hover:bg-white/10 hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                        Profile
                    </a>
                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'bg-amber-300 text-slate-950' : 'text-slate-200 hover:bg-amber-300/20 hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="/" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white">View site</a>
                <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-right">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-300">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-100">
                        Log out
                    </button>
                </form>
            </div>

            <button @click="open = ! open" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-slate-100 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        <div x-cloak x-show="open" class="mt-4 grid gap-2 border-t border-white/10 pt-4 lg:hidden">
            <a href="{{ route('dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">My Trips</a>
            <a href="{{ route('itineraries.popular') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Trending</a>
            <a href="{{ route('profile.edit') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Profile</a>
            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Admin</a>
            @endif
            <a href="/" class="rounded-2xl px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">View site</a>

            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-300">{{ Auth::user()->email }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-950">
                    Log out
                </button>
            </form>
        </div>
    </div>
</nav>
