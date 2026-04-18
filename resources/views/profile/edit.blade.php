<x-app-layout>
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="hero-kicker">Profile settings</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Keep your traveler profile polished and secure.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Update your personal details, refresh your password, and control access to the account behind your itineraries.
                    </p>
                </div>
            </div>
        </section>
    </x-slot>

    <div class="space-y-6 pb-12">
        <section class="surface-panel p-4 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <section class="surface-panel p-4 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <section class="surface-panel p-4 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
</x-app-layout>
