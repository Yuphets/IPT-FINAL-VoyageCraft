@php
    $entries = ['resources/css/app.css', 'resources/js/app.js'];
    $hotFile = public_path('hot');
    $manifestPath = public_path('build/manifest.json');
    $host = request()->getHost();
    $localHosts = ['127.0.0.1', 'localhost', '::1'];
    $shouldUseHotServer = file_exists($hotFile) && in_array($host, $localHosts, true);
@endphp

@if($shouldUseHotServer || !file_exists($manifestPath))
    @vite($entries)
@else
    @php
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $cssEntry = $manifest['resources/css/app.css']['file'] ?? null;
        $jsEntry = $manifest['resources/js/app.js']['file'] ?? null;
        $jsCss = $manifest['resources/js/app.js']['css'] ?? [];
    @endphp

    @if($cssEntry)
        <link rel="stylesheet" href="{{ asset('build/' . $cssEntry) }}">
    @endif

    @foreach($jsCss as $cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endforeach

    @if($jsEntry)
        <script type="module" src="{{ asset('build/' . $jsEntry) }}"></script>
    @endif
@endif
