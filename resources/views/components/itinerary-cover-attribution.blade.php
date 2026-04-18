@props(['itinerary', 'class' => ''])

@if($itinerary->hasExternalCoverImage())
    <p {{ $attributes->merge(['class' => trim('text-xs leading-5 text-slate-500 ' . $class)]) }}>
        Photo by
        <a href="{{ $itinerary->cover_image_author_url }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-teal-700 hover:text-teal-800">
            {{ $itinerary->cover_image_author_name }}
        </a>
        on
        <a href="{{ $itinerary->cover_image_source_url }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-teal-700 hover:text-teal-800">
            Unsplash
        </a>
    </p>
@endif
