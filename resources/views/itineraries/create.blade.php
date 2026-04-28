<x-app-layout>
    @php
        $sidebarCover = \App\Models\Itinerary::whereNotNull('cover_image_remote_url')->latest()->first();
    @endphp
    <x-slot name="header">
        <section class="surface-panel-dark overflow-hidden p-8 sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="hero-kicker">Create itinerary</p>
                    <h1 class="mt-4 text-5xl font-semibold leading-none text-white sm:text-6xl">Build a polished trip plan from the first detail.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                        Name the journey, lock in dates, and add a cover that makes the itinerary feel instantly professional.
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Back to dashboard
                </a>
            </div>
        </section>
    </x-slot>

    <div class="grid gap-8 pb-12 lg:grid-cols-[1.15fr_0.85fr]">
        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('itineraries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="title" :value="__('Trip title')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title')" required autofocus placeholder="Tokyo design week, Greek island hopping, Cebu diving escape..." />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="5" class="w-full rounded-[24px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10" placeholder="Add goals, trip tone, must-visit highlights, or special planning notes...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <x-input-label for="start_date" :value="__('Start date')" />
                        <x-text-input id="start_date" name="start_date" type="date" :value="old('start_date')" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('End date')" />
                        <x-text-input id="end_date" name="end_date" type="date" :value="old('end_date')" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-4">
                        <x-input-label for="cover_image" :value="__('Cover image')" class="mb-0" />
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Optional</span>
                    </div>
                    <p class="mt-2 text-sm leading-7 text-slate-600">Search a real place photo first, or upload your own cover if you already have one.</p>

                    <div class="mt-4 rounded-[28px] border border-slate-200 bg-slate-50/80 p-5">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input
                                id="cover-search-query"
                                type="text"
                                value="{{ old('title') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10"
                                placeholder="Search a place like Tokyo Tower, Santorini, or Banff"
                            >
                            <button type="button" id="cover-search-button" class="rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                                Find real photos
                            </button>
                        </div>
                        <p id="cover-search-status" class="mt-3 text-sm text-slate-500">Search by city, landmark, island, national park, or district.</p>
                        <div id="cover-search-results" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3"></div>
                    </div>

                    <label for="cover_image" class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-[28px] border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-teal-500 hover:bg-teal-50/40">
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                            <svg class="h-7 w-7 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0l-4 4m4-4l4 4M4 16.5v1.5A2.5 2.5 0 006.5 20h11A2.5 2.5 0 0020 17.5V16" />
                            </svg>
                        </span>
                        <span class="mt-4 text-base font-semibold text-slate-900">Upload a JPG or PNG if you want to override the search result.</span>
                        <span class="mt-2 text-sm text-slate-500">Uploading a file takes priority over the selected API photo.</span>
                        <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                    </label>
                    <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />

                    <div id="image-preview" class="mt-4 hidden overflow-hidden rounded-[24px] border border-slate-200">
                        <img id="preview-img" src="#" alt="Cover preview" class="h-56 w-full object-cover">
                    </div>
                    <p id="selected-image-credit" class="mt-3 hidden text-sm leading-6 text-slate-500"></p>

                    <input type="hidden" name="selected_cover_image_provider" id="selected_cover_image_provider" value="{{ old('selected_cover_image_provider') }}">
                    <input type="hidden" name="selected_cover_image_url" id="selected_cover_image_url" value="{{ old('selected_cover_image_url') }}">
                    <input type="hidden" name="selected_cover_image_author_name" id="selected_cover_image_author_name" value="{{ old('selected_cover_image_author_name') }}">
                    <input type="hidden" name="selected_cover_image_author_url" id="selected_cover_image_author_url" value="{{ old('selected_cover_image_author_url') }}">
                    <input type="hidden" name="selected_cover_image_source_url" id="selected_cover_image_source_url" value="{{ old('selected_cover_image_source_url') }}">
                    <input type="hidden" name="selected_cover_image_download_location" id="selected_cover_image_download_location" value="{{ old('selected_cover_image_download_location') }}">
                </div>

                <label for="is_public" class="flex items-start gap-4 rounded-[24px] border border-slate-200 bg-slate-50/80 px-5 py-4">
                    <input id="is_public" type="checkbox" class="mt-1 rounded border-slate-300 text-teal-700 focus:ring-teal-500" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}>
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">Enable public sharing</span>
                        <span class="mt-1 block text-sm text-slate-600">Allow this itinerary to be viewed through shared links and QR codes.</span>
                    </span>
                </label>

                <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6">
                    <a href="{{ route('dashboard') }}" class="button-ghost">Cancel</a>
                    <x-primary-button>Create itinerary</x-primary-button>
                </div>
            </form>
        </section>

        <aside class="space-y-6">
            <section class="surface-panel overflow-hidden">
                <img src="{{ $sidebarCover?->cover_image_url ?? \App\Models\Itinerary::themeImageUrl('heritage') }}" alt="Trip inspiration visual" class="h-52 w-full object-cover" />
                <div class="p-6">
                    <p class="soft-badge">Planning tip</p>
                    <h2 class="mt-4 text-3xl font-semibold text-slate-950">A strong itinerary starts with a clear trip story.</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Use the description to note the tone of the trip, must-see stops, and the kind of experience you want travelers to remember.
                    </p>
                </div>
            </section>

            <section class="surface-panel p-6">
                <h3 class="text-3xl font-semibold text-slate-950">What you unlock next</h3>
                <div class="mt-5 space-y-4 text-sm leading-7 text-slate-600">
                    <div class="rounded-[24px] bg-slate-50 px-4 py-3">Add destination stops with arrival and departure times.</div>
                    <div class="rounded-[24px] bg-slate-50 px-4 py-3">Share the itinerary publicly with a single QR code.</div>
                    <div class="rounded-[24px] bg-slate-50 px-4 py-3">Download a PDF version for printouts or quick briefing packs.</div>
                </div>
            </section>
        </aside>
    </div>

    @push('scripts')
        <script>
            (() => {
                const coverInput = document.getElementById('cover_image');
                const preview = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');
                const credit = document.getElementById('selected-image-credit');
                const queryInput = document.getElementById('cover-search-query');
                const searchButton = document.getElementById('cover-search-button');
                const status = document.getElementById('cover-search-status');
                const results = document.getElementById('cover-search-results');

                const hiddenFields = {
                    provider: document.getElementById('selected_cover_image_provider'),
                    url: document.getElementById('selected_cover_image_url'),
                    authorName: document.getElementById('selected_cover_image_author_name'),
                    authorUrl: document.getElementById('selected_cover_image_author_url'),
                    sourceUrl: document.getElementById('selected_cover_image_source_url'),
                    downloadLocation: document.getElementById('selected_cover_image_download_location'),
                };

                const setPreview = (src) => {
                    previewImg.src = src;
                    preview.classList.remove('hidden');
                };

                const clearSelectedPhoto = () => {
                    Object.values(hiddenFields).forEach((field) => field.value = '');
                    credit.textContent = '';
                    credit.classList.add('hidden');
                    Array.from(results.querySelectorAll('[data-photo-id]')).forEach((button) => {
                        button.classList.remove('ring-2', 'ring-teal-500');
                    });
                };

                const setCredit = (photo) => {
                    credit.replaceChildren();
                    credit.append('Selected photo by ');

                    const photographerLink = document.createElement('a');
                    photographerLink.href = photo.photographer_url;
                    photographerLink.target = '_blank';
                    photographerLink.rel = 'noopener noreferrer';
                    photographerLink.className = 'font-semibold text-teal-700 hover:text-teal-800';
                    photographerLink.textContent = photo.photographer_name;

                    const sourceLink = document.createElement('a');
                    sourceLink.href = photo.source_url;
                    sourceLink.target = '_blank';
                    sourceLink.rel = 'noopener noreferrer';
                    sourceLink.className = 'font-semibold text-teal-700 hover:text-teal-800';
                    sourceLink.textContent = 'Unsplash';

                    credit.append(photographerLink, ' on ', sourceLink, '.');
                    credit.classList.remove('hidden');
                };

                const selectPhoto = (photo, button) => {
                    hiddenFields.provider.value = 'unsplash';
                    hiddenFields.url.value = photo.image_url;
                    hiddenFields.authorName.value = photo.photographer_name;
                    hiddenFields.authorUrl.value = photo.photographer_url;
                    hiddenFields.sourceUrl.value = photo.source_url;
                    hiddenFields.downloadLocation.value = photo.download_location;

                    coverInput.value = '';
                    setPreview(photo.image_url);
                    setCredit(photo);

                    Array.from(results.querySelectorAll('[data-photo-id]')).forEach((item) => {
                        item.classList.remove('ring-2', 'ring-teal-500');
                    });
                    button.classList.add('ring-2', 'ring-teal-500');
                };

                const renderResults = (photos) => {
                    results.innerHTML = '';

                    if (!photos.length) {
                        status.textContent = 'No real place photos matched that search. Try a more specific landmark or city.';
                        return;
                    }

                    status.textContent = `Found ${photos.length} real photo option${photos.length === 1 ? '' : 's'}.`;

                    photos.forEach((photo) => {
                        const card = document.createElement('button');
                        const image = document.createElement('img');
                        const body = document.createElement('div');
                        const title = document.createElement('p');
                        const byline = document.createElement('p');

                        card.type = 'button';
                        card.dataset.photoId = photo.id;
                        card.className = 'overflow-hidden rounded-[24px] border border-slate-200 bg-white text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md';

                        image.src = photo.thumbnail_url;
                        image.alt = photo.description;
                        image.className = 'h-40 w-full object-cover';

                        body.className = 'p-4';
                        title.className = 'text-sm font-semibold text-slate-900';
                        title.textContent = photo.description;

                        byline.className = 'mt-2 text-xs leading-6 text-slate-500';
                        byline.textContent = `Photo by ${photo.photographer_name}`;

                        body.append(title, byline);
                        card.append(image, body);
                        card.addEventListener('click', () => selectPhoto(photo, card));
                        results.appendChild(card);
                    });
                };

                const searchPhotos = async () => {
                    const query = queryInput.value.trim();

                    if (query.length < 2) {
                        status.textContent = 'Enter at least two characters to search for a real place photo.';
                        return;
                    }

                    searchButton.disabled = true;
                    status.textContent = 'Searching real place photos...';

                    try {
                        const response = await window.axios.get('{{ url('/place-images/search') }}', {
                            params: { query },
                        });
                        renderResults(response.data.data ?? []);
                    } catch (error) {
                        status.textContent = error.response?.data?.message ?? 'Place photo search is unavailable right now.';
                        results.innerHTML = '';
                    } finally {
                        searchButton.disabled = false;
                    }
                };

                coverInput.addEventListener('change', (event) => {
                    const file = event.target.files[0];

                    if (file) {
                        clearSelectedPhoto();
                        setPreview(URL.createObjectURL(file));
                    } else if (!hiddenFields.url.value) {
                        preview.classList.add('hidden');
                    }
                });

                searchButton.addEventListener('click', searchPhotos);
                queryInput.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchPhotos();
                    }
                });

                if (hiddenFields.url.value) {
                    setPreview(hiddenFields.url.value);
                    setCredit({
                        photographer_name: hiddenFields.authorName.value,
                        photographer_url: hiddenFields.authorUrl.value,
                        source_url: hiddenFields.sourceUrl.value,
                    });
                }
            })();
        </script>
    @endpush
</x-app-layout>
