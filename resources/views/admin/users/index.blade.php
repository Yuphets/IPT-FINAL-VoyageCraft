<x-app-layout>
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="hero-kicker">User management</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Manage traveler access with a clearer administrative view.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Promote admins, monitor itinerary counts, and keep the workspace clean without losing context.
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Back to admin dashboard
                </a>
            </div>
        </section>
    </x-slot>

    <div class="space-y-8 pb-12">
        <section class="surface-panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4 sm:px-8">User</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Itineraries</th>
                            <th class="px-6 py-4">Registered</th>
                            <th class="px-6 py-4 text-right sm:pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white text-sm text-slate-600">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-5 sm:px-8">
                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="rounded-full {{ $user->hasRole('admin') ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }} px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em]">
                                        {{ $user->hasRole('admin') ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">{{ $user->itineraries_count ?? $user->itineraries->count() }}</td>
                                <td class="px-6 py-5">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-5 sm:pr-8">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <form action="{{ route('admin.users.role', $user) }}" method="POST">
                                            @csrf
                                            <select name="role" onchange="this.form.submit()" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10">
                                                <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User</option>
                                                <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This will also delete all their itineraries.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-500">Current account</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-4 py-4">
                {{ $users->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
