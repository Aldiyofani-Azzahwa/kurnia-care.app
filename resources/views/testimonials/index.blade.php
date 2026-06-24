@extends('layouts.admin')

@section('title', 'Kelola Testimoni')

@section('content')

@if (session('success'))
    <div class="mb-6 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3">
        {{ session('success') }}
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

        <div class="overflow-x-auto">
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

                            <td class="px-4 py-3 font-semibold">
                                {{ $testimonial->name }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                <p class="line-clamp-2">
                                    {{ $testimonial->content }}
                                </p>
                            </td>

                            <td class="px-4 py-3 text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $testimonial->rating ? '★' : '☆' }}
                                @endfor
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
                                        class="px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
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

            <a href="{{ route('admin.testimonials.create') }}"
                class="inline-block px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Tambah Testimoni
            </a>
        </div>
    @endif

</div>

@endsection