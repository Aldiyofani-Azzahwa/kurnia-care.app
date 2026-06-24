@extends('layouts.admin')

@section('title', 'Tambah Testimoni')

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
                Tambah Testimoni
            </h3>

            <p class="text-sm text-gray-500">
                Tambahkan testimoni orang tua pasien yang akan tampil di homepage.
            </p>
        </div>

        <a href="{{ route('admin.testimonials.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Orang Tua
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Ibu Rina"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Foto Testimoni
            </label>

            <input
                type="file"
                name="image"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Format jpg, jpeg, png, atau webp. Maksimal 2 MB.
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
                <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>5 Bintang</option>
                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Bintang</option>
                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Bintang</option>
                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Bintang</option>
                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Bintang</option>
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
            >{{ old('content') }}</textarea>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
            <label class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    {{ old('is_active', '1') ? 'checked' : '' }}
                >

                <span class="text-sm font-medium text-gray-700">
                    Tampilkan testimoni di homepage
                </span>
            </label>

            <p class="text-xs text-gray-500 mt-2">
                Jika aktif, testimoni ini akan muncul pada section testimoni homepage.
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.testimonials.index') }}"
                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Batal
            </a>

            <button type="submit"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Simpan Testimoni
            </button>
        </div>

    </form>

</div>

@endsection