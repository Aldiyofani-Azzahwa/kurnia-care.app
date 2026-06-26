@php
    $manifestPath = public_path('build/manifest.json');

    $manifest = file_exists($manifestPath)
        ? json_decode(file_get_contents($manifestPath), true)
        : [];

    $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
    $jsFile = $manifest['resources/js/app.js']['file'] ?? null;

    $cssPath = $cssFile ? public_path('build/' . $cssFile) : null;
@endphp

@if ($cssPath && file_exists($cssPath))
    <style>
        {!! file_get_contents($cssPath) !!}
    </style>
@endif

@if ($jsFile)
    <script type="module" src="/build/{{ $jsFile }}"></script>
@endif