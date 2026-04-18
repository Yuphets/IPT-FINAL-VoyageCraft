<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Itineraries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('itineraries.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    + Create New Itinerary
                </a>
            </div>

            @if($itineraries->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($itineraries as $itinerary)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <img src="{{ $itinerary->cover_image_url }}" alt="{{ $itinerary->title }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">{{ $itinerary->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $itinerary->start_date->format('M d, Y') }} - {{ $itinerary->end_date->format('M d, Y') }}
                                </p>
                                <x-itinerary-cover-attribution :itinerary="$itinerary" class="mb-4" />
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('itineraries.show', $itinerary) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                    <span class="text-sm text-gray-500">{{ $itinerary->destinations->count() }} stops</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $itineraries->links() }}
            @else
                <p class="text-gray-500">You haven't created any itineraries yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
