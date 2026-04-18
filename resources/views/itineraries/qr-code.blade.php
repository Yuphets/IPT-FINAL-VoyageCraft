<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            QR Code for {{ $itinerary->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <h3 class="text-lg mb-4">Scan to view this itinerary</h3>
                    <div class="inline-block p-4 border-2 border-gray-200 rounded-lg">
                        {!! $qrCode !!}
                    </div>
                    <p class="mt-4 text-gray-600">Anyone with this QR code can view the itinerary.</p>
                    <div class="mt-6">
                        <a href="{{ route('itineraries.show', $itinerary) }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Back to Itinerary
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
