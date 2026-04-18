<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VoyageCraft') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|cormorant-garamond:500,600,700&display=swap" rel="stylesheet" />

        <x-app-assets />
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[26rem] bg-[radial-gradient(circle_at_top,rgba(34,211,238,0.16),transparent_45%)]"></div>
            <div class="pointer-events-none absolute right-0 top-16 -z-10 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl"></div>

            @include('layouts.navigation')

            <div class="page-wrap relative py-8 sm:py-10">
                @isset($header)
                    <header class="mb-8">
                        {{ $header }}
                    </header>
                @endisset

                @if (session('success'))
                    <div class="surface-panel mb-6 flex items-start gap-3 px-5 py-4 text-sm text-slate-700">
                        <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-teal-500/15 text-teal-700">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3.2-3.2a1 1 0 111.415-1.42l2.493 2.494 6.493-6.494a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900">Saved successfully</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="surface-panel mb-6 flex items-start gap-3 px-5 py-4 text-sm text-rose-700">
                        <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-500/15 text-rose-700">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4a1 1 0 112 0 1 1 0 01-2 0zm0-8a1 1 0 012 0v5a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900">Something needs attention</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
