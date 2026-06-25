@extends('layouts.admin')

@section('title', 'Tambah Layanan')

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
                Tambah Layanan
            </h3>

            <p class="text-sm text-gray-500">
                Tambahkan layanan khitan yang akan ditampilkan di sistem.
            </p>
        </div>

        <a href="{{ route('admin.services.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form
        action="{{ route('admin.services.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Layanan
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Paket Khitan Modern"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Harga Layanan
            </label>

            <input
                type="number"
                name="price"
                value="{{ old('price') }}"
                placeholder="Contoh: 750000"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Isi angka saja, tanpa titik atau Rp.
            </p>

            @error('price')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Durasi Layanan
            </label>

            <input
                type="number"
                name="duration_minutes"
                value="{{ old('duration_minutes', 30) }}"
                placeholder="Contoh: 30"
                class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >

            <p class="text-xs text-gray-500 mt-1">
                Durasi dalam menit.
            </p>

            @error('duration_minutes')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Gambar Layanan
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

            @error('image')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Deskripsi
            </label>

            <textarea
                name="description"
                rows="5"
                placeholder="Contoh: Paket khitan modern dengan pelayanan standar klinik."
                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none"
            >{{ old('description') }}</textarea>

            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
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
                    Aktifkan layanan
                </span>
            </label>

            <p class="text-xs text-gray-500 mt-2">
                Jika aktif, layanan ini dapat dipilih oleh pasien saat melakukan pendaftaran.
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.services.index') }}"
                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Dibatalkan
            </a>

            <button
                type="submit"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700"
            >
                Simpan Layanan
            </button>
        </div>
    </form>

</div>

@endsection