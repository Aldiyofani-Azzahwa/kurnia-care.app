@php
    $photoValue = $patient?->child_photo;
    $photoUrl = null;

    if ($photoValue) {
        if (str_starts_with($photoValue, 'http://') || str_starts_with($photoValue, 'https://')) {
            $photoUrl = $photoValue;
        } else {
            $photoUrl = asset('storage/' . $photoValue);
        }
    }
@endphp

<div class="mb-5">
    <p class="text-sm text-gray-500 mb-2">Foto Anak</p>

    @if ($photoUrl)
        <a href="{{ $photoUrl }}" target="_blank" class="inline-block">
            <img
                src="{{ $photoUrl }}"
                alt="Foto Anak {{ $patient->child_name ?? '' }}"
                class="w-40 h-40 rounded-xl border border-gray-200 object-cover bg-gray-50"
                onerror="this.onerror=null; this.src='https://placehold.co/300x300?text=Foto+Tidak+Terload';"
            >
        </a>

        <p class="mt-2 text-xs text-gray-500">
            Klik foto untuk melihat ukuran penuh.
        </p>
    @else
        <div class="w-40 h-40 rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-sm text-gray-400 text-center px-3">
            Belum ada foto anak.
        </div>
    @endif
</div>