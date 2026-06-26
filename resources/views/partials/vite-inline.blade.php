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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const supabaseBase = 'https://ofilaorrxscorekgtvrr.supabase.co';
        const bucket = 'kurnia-care';

        document.querySelectorAll('img').forEach(function (img) {
            let src = img.getAttribute('src');

            if (!src) return;

            // Kalau gambar sudah benar dari Supabase, biarkan
            if (src.startsWith(supabaseBase)) return;

            // Cek gambar yang masih dipanggil dari /storage/...
            const marker = '/storage/';
            const index = src.indexOf(marker);

            if (index === -1) return;

            let path = src.substring(index + marker.length);
            path = decodeURIComponent(path);

            // Perbaiki URL rusak seperti https:/ofila...
            path = path.replace(/^https:\/(?!\/)/, 'https://');
            path = path.replace(/^http:\/(?!\/)/, 'http://');

            // Kalau ternyata path sudah URL lengkap
            if (path.startsWith('https://') || path.startsWith('http://')) {
                img.src = path;
                return;
            }

            // Bersihkan path
            path = path.replace(/^\/+/, '');
            path = path.replace(/^public\//, '');
            path = path.replace(/^kurnia-care\//, '');

            // Arahkan ke Supabase Storage
            img.src = `${supabaseBase}/storage/v1/object/public/${bucket}/${path}`;
        });
    });
</script>