@extends('layouts.admin')

@section('title', 'Edit Testimoni')

@section('content')

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
        <strong>Data belum lengkap.</strong>

        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-emerald-700">
                Edit Testimoni
            </h3>

            <p class="text-sm text-gray-500">
                Perbarui testimoni orang tua pasien yang tampil di homepage.
            </p>
        </div>

        <a href="{{ route('admin.testimonials.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form
        action="{{ route('admin.testimonials.update', $testimonial) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Orang Tua
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $testimonial->name) }}"
                placeholder="Contoh: Ibu Rina"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Foto Testimoni
            </label>

            @if ($testimonial->image)
                <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <img
                            src="{{ asset('storage/' . $testimonial->image) }}"
                            alt="{{ $testimonial->name }}"
                            class="h-24 w-24 rounded-full object-cover border border-gray-200 bg-white"
                        >

                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Foto saat ini
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Centang opsi hapus jika ingin menghapus foto ini.
                            </p>

                            <label class="mt-3 flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    name="remove_image"
                                    value="1"
                                    class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                                >

                                <span class="text-sm font-medium text-gray-700">
                                    Hapus foto testimoni saat ini
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            @else
                <div class="mb-4 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4 text-sm text-gray-500">
                    Belum ada foto testimoni.
                </div>
            @endif

            <input
                type="file"
                name="image"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Kosongkan jika tidak ingin mengganti foto. Format jpg, jpeg, png, atau webp. Maksimal 2 MB.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Rating
            </label>

            <select
                name="rating"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >
                <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>
                    5 Bintang
                </option>

                <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>
                    4 Bintang
                </option>

                <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>
                    3 Bintang
                </option>

                <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>
                    2 Bintang
                </option>

                <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>
                    1 Bintang
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Isi Testimoni
            </label>

            <textarea
                name="content"
                rows="5"
                placeholder="Contoh: Pelayanan sangat ramah, anak saya merasa nyaman, dan prosesnya cepat."
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >{{ old('content', $testimonial->content) }}</textarea>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <label class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}
                >

                <span class="text-sm font-medium text-gray-700">
                    Tampilkan testimoni di homepage
                </span>
            </label>

            <p class="text-xs text-gray-500 mt-2">
                Jika aktif, testimoni ini akan tampil pada section testimoni homepage.
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.testimonials.index') }}"
                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Batal
            </a>

            <button type="submit"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

@endsection