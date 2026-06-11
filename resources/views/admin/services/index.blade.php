@extends('layouts.admin')

@section('title', 'Kelola Layanan')

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
                    Data Layanan / Paket Khitan
                </h3>

                <p class="text-sm text-gray-500">
                    Kelola layanan yang tampil di form pendaftaran pasien.
                </p>
            </div>

            <a href="{{ route('admin.services.create') }}"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-center">
                Tambah Layanan
            </a>
        </div>

        @if ($services->count() > 0)

            {{-- MOBILE CARD --}}
            <div class="md:hidden space-y-4">
                @foreach ($services as $service)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">

                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="font-bold text-emerald-700">
                                    {{ $service->name }}
                                </h4>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $service->slug }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if ($service->is_active)
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

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between gap-4 border-t pt-3">
                                <span class="text-gray-500">Harga</span>
                                <span class="font-bold text-emerald-700 text-right">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Durasi</span>
                                <span class="font-semibold text-right">
                                    {{ $service->duration_minutes }} menit
                                </span>
                            </div>

                            <div>
                                <p class="text-gray-500">Deskripsi</p>
                                <p class="font-medium mt-1">
                                    {{ $service->description ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}"
                                class="block w-full text-center px-4 py-3 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500 font-semibold">
                                Edit
                            </a>

                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Harga</th>
                            <th class="px-4 py-3 text-left">Durasi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($services as $service)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold">
                                        {{ $service->name }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $service->description ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $service->duration_minutes }} menit
                                </td>

                                <td class="px-4 py-3">
                                    @if ($service->is_active)
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
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="inline-block px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
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
                {{ $services->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">
                    Belum ada layanan.
                </p>

                <p class="text-sm text-gray-400 mb-5">
                    Tambahkan layanan/paket khitan terlebih dahulu.
                </p>

                <a href="{{ route('admin.services.create') }}"
                    class="inline-block px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                    Tambah Layanan
                </a>
            </div>

        @endif

    </div>

@endsection