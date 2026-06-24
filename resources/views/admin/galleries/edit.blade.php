@extends('layouts.admin')

@section('title', 'Edit Dokumentasi')

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
                Edit Dokumentasi
            </h3>

            <p class="text-sm text-gray-500">
                Perbarui foto dokumentasi yang tampil di homepage.
            </p>
        </div>

        <a href="{{ route('admin.galleries.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">
                Judul Dokumentasi
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title', $gallery->title) }}"
                placeholder="Contoh: Foto bersama pasien dan keluarga"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Gambar Dokumentasi
            </label>

            <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-medium text-gray-700 mb-3">
                    Gambar saat ini
                </p>

                <img
                    src="{{ asset('storage/' . $gallery->image) }}"
                    alt="{{ $gallery->title }}"
                    class="h-56 w-full max-w-md rounded-xl object-cover border border-gray-200 bg-white"
                >
            </div>

            <input
                type="file"
                name="image"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Kosongkan jika tidak ingin mengganti gambar. Format jpg, jpeg, png, atau webp. Maksimal 2 MB.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Deskripsi
            </label>

            <textarea
                name="description"
                rows="4"
                placeholder="Contoh: Dokumentasi pasien setelah tindakan bersama keluarga."
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >{{ old('description', $gallery->description) }}</textarea>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <label class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                >

                <span class="text-sm font-medium text-gray-700">
                    Tampilkan dokumentasi di homepage
                </span>
            </label>
        </div>

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.galleries.index') }}"
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