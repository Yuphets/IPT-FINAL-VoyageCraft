<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="soft-badge">Create account</p>
            <h1 class="mt-4 text-4xl font-semibold text-slate-950">Start planning polished journeys</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Set up your account to build travel timelines, add destination details, and share trip plans in a more professional format.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Full name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email address')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full">
                {{ __('Create account') }}
            </x-primary-button>
        </form>

        <div class="rounded-[24px] bg-slate-50 px-5 py-4 text-sm leading-7 text-slate-600">
            Already a member?
            <a href="{{ route('login') }}" class="font-semibold text-teal-700 transition hover:text-teal-800">Log in here</a>
            to continue managing your travel plans.
        </div>
    </div>
</x-guest-layout>
