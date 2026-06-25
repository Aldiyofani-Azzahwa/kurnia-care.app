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
                Perbarui data testimoni.
            </p>
        </div>

        <a href="{{ route('admin.testimonials.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Foto Testimoni
            </label>

            @if ($testimonial->image)
                <div class="mb-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <img
                        src="{{ asset('storage/' . $testimonial->image) }}"
                        alt="{{ $testimonial->name }}"
                        class="h-24 w-24 rounded-full object-cover"
                    >

                    <label class="mt-3 flex items-center gap-3">
                        <input
                            type="checkbox"
                            name="remove_image"
                            value="1"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                        >

                        <span class="text-sm font-medium text-gray-700">
                            Hapus foto saat ini
                        </span>
                    </label>
                </div>
            @endif

            <input
                type="file"
                name="image"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Kosongkan jika tidak ingin mengganti foto.
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
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>
                        {{ $i }} Bintang
                    </option>
                @endfor
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Isi Testimoni
            </label>

            <textarea
                name="content"
                rows="5"
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
                    @checked(old('is_active', $testimonial->is_active))
                >

                <span class="text-sm font-medium text-gray-700">
                    Tampilkan di homepage
                </span>
            </label>
        </div>

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.testimonials.index') }}"
                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Dibatalkan
            </a>

            <button type="submit"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@endsection