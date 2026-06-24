@extends('layouts.admin')

@section('title', 'Kelola Dokumentasi')

@section('content')

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-emerald-700">
                    Data Dokumentasi
                </h3>

                <p class="text-sm text-gray-500">
                    Kelola dokumentasi pasien, keluarga, dokter, dan kegiatan yang tampil di homepage.
                </p>
            </div>

            <a href="{{ route('admin.galleries.create') }}"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-center">
                Tambah Dokumentasi
            </a>
        </div>

        @if ($galleries->count() > 0)

            {{-- MOBILE CARD --}}
            <div class="md:hidden space-y-4">
                @foreach ($galleries as $gallery)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                            class="h-48 w-full object-cover">

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-emerald-700">
                                        {{ $gallery->title }}
                                    </h4>

                                    <p class="mt-2 text-sm text-gray-600">
                                        {{ $gallery->description ?? '-' }}
                                    </p>
                                </div>

                                <div class="shrink-0">
                                    @if ($gallery->is_active)
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-2">
                                <a href="{{ route('admin.galleries.edit', $gallery) }}"
                                    class="block w-full text-center px-4 py-3 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500 font-semibold">
                                    Edit
                                </a>

                                <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus dokumentasi ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Gambar</th>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Deskripsi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($galleries as $gallery)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                                        class="h-16 w-24 rounded-lg object-cover border border-gray-200">
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold">
                                        {{ $gallery->title }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 max-w-md">
                                    <p class="text-gray-600 line-clamp-2">
                                        {{ $gallery->description ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($gallery->is_active)
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.galleries.edit', $gallery) }}"
                                            class="inline-block px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus dokumentasi ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $galleries->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">
                    Belum ada dokumentasi.
                </p>

                <p class="text-sm text-gray-400 mb-5">
                    Tambahkan foto dokumentasi pasien, keluarga, dokter, atau kegiatan.
                </p>

                <a href="{{ route('admin.galleries.create') }}"
                    class="inline-block px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                    Tambah Dokumentasi
                </a>
            </div>

        @endif

    </div>

@endsection