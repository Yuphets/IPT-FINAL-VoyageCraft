<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="soft-badge">Secure sign in</p>
            <h1 class="mt-4 text-4xl font-semibold text-slate-950">Welcome back</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Sign in to manage itineraries, export polished travel plans, and keep every destination stop in sync.
            </p>
        </div>

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email address')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between gap-4">
                    <x-input-label for="password" :value="__('Password')" class="mb-0" />
                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold text-teal-700 transition hover:text-teal-800" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-700 focus:ring-teal-500" name="remember">
                <span>Keep me signed in on this device</span>
            </label>

            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </form>

        <div class="rounded-[24px] bg-slate-50 px-5 py-4 text-sm leading-7 text-slate-600">
            New to VoyageCraft?
            <a href="{{ route('register') }}" class="font-semibold text-teal-700 transition hover:text-teal-800">Create your account</a>
            and start building professional trip plans today.
        </div>
    </div>
</x-guest-layout>
