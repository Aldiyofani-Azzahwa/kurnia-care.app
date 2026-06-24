@extends('layouts.admin')

@section('title', 'Kelola Testimoni')

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
                    Data Testimoni
                </h3>

                <p class="text-sm text-gray-500">
                    Kelola testimoni orang tua pasien yang tampil di homepage.
                </p>
            </div>

            <a href="{{ route('admin.testimonials.create') }}"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-center">
                Tambah Testimoni
            </a>
        </div>

        @if ($testimonials->count() > 0)

            {{-- MOBILE CARD --}}
            <div class="md:hidden space-y-4">
                @foreach ($testimonials as $testimonial)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">

                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                @if ($testimonial->image)
                                    <img
                                        src="{{ asset('storage/' . $testimonial->image) }}"
                                        alt="{{ $testimonial->name }}"
                                        class="h-14 w-14 rounded-full object-cover"
                                    >
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-700">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-emerald-700">
                                    {{ $testimonial->name }}
                                </h4>

                                <div class="mt-1 text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $testimonial->rating ? '★' : '☆' }}
                                    @endfor
                                </div>

                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $testimonial->content }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                @if ($testimonial->is_active)
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
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                class="block w-full text-center px-4 py-3 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500 font-semibold">
                                Edit
                            </a>

                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                            <th class="px-4 py-3 text-left">Foto</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Testimoni</th>
                            <th class="px-4 py-3 text-left">Rating</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($testimonials as $testimonial)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    @if ($testimonial->image)
                                        <img
                                            src="{{ asset('storage/' . $testimonial->image) }}"
                                            alt="{{ $testimonial->name }}"
                                            class="h-12 w-12 rounded-full object-cover"
                                        >
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-700">
                                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold">
                                        {{ $testimonial->name }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 max-w-md">
                                    <p class="text-gray-600 line-clamp-2">
                                        {{ $testimonial->content }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $testimonial->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($testimonial->is_active)
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
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                            class="inline-block px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                {{ $testimonials->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">
                    Belum ada testimoni.
                </p>

                <p class="text-sm text-gray-400 mb-5">
                    Tambahkan testimoni orang tua pasien terlebih dahulu.
                </p>

                <a href="{{ route('admin.testimonials.create') }}"
                    class="inline-block px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                    Tambah Testimoni
                </a>
            </div>

        @endif

    </div>

@endsection