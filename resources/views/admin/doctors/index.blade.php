@extends('layouts.admin')

@section('title', 'Kelola Dokter')

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

{{-- STATISTIK --}}
<div class="mb-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-xs text-gray-500">Total Dokter</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">
            {{ $totalDoctors }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Dokter Aktif</p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">
            {{ $activeDoctors }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Tidak Aktif</p>
        <h3 class="text-3xl font-bold text-red-500 mt-2">
            {{ $inactiveDoctors }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Total Appointment</p>
        <h3 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $totalAppointments }}
        </h3>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-emerald-700">
                Data Dokter
            </h3>

            <p class="text-sm text-gray-500">
                Kelola data dokter dan akun login dokter Kurnia Care.
            </p>
        </div>

        <a href="{{ route('admin.doctors.create') }}"
           class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 text-center">
            + Tambah Dokter
        </a>
    </div>

    {{-- SEARCH & FILTER --}}
    <form method="GET"
          action="{{ route('admin.doctors.index') }}"
          class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Cari nama, email, SIP, spesialis..."
               class="md:col-span-2 w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

        <select name="status"
                class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="">Semua Status</option>
            <option value="1" @selected($status === '1')>Aktif</option>
            <option value="0" @selected($status === '0')>Tidak Aktif</option>
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-5 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700">
                Cari
            </button>

            <a href="{{ route('admin.doctors.index') }}"
               class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl text-center hover:bg-gray-300">
                Reset
            </a>
        </div>
    </form>

    @if ($doctors->count() > 0)

        {{-- MODE HP / MOBILE: CARD --}}
        <div class="md:hidden space-y-4">
            @foreach ($doctors as $doctor)
                <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-4 shadow-sm">

                    <div class="flex items-start gap-4">
                        @if ($doctor->photo)
                            <img src="{{ asset('storage/' . $doctor->photo) }}"
                                 alt="{{ $doctor->name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl font-bold text-emerald-700">
                                {{ strtoupper(substr($doctor->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900">
                                {{ $doctor->name }}
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $doctor->specialist ?? 'Dokter Klinik' }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1 truncate">
                                {{ $doctor->user->email ?? '-' }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $doctor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $doctor->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">No HP</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $doctor->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">SIP</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $doctor->sip_number ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">Appointment</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $doctor->appointments_count }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">Jadwal</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $doctor->schedules_count }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <a href="{{ route('admin.doctors.show', $doctor) }}"
                           class="py-2 text-center bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                            Detail
                        </a>

                        <a href="{{ route('admin.doctors.edit', $doctor) }}"
                           class="py-2 text-center bg-amber-400 text-gray-900 rounded-xl text-sm font-medium hover:bg-amber-500">
                            Edit
                        </a>

                        <form action="{{ route('admin.doctors.destroy', $doctor) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full py-2 text-center bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- MODE DESKTOP: TABEL --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-emerald-700 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Dokter</th>
                        <th class="px-4 py-3 text-left">Kontak</th>
                        <th class="px-4 py-3 text-left">SIP</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Statistik</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($doctors as $doctor)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($doctor->photo)
                                        <img src="{{ asset('storage/' . $doctor->photo) }}"
                                             alt="{{ $doctor->name }}"
                                             class="w-12 h-12 rounded-xl object-cover border">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-lg font-bold text-emerald-700">
                                            {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $doctor->name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $doctor->specialist ?? 'Dokter Klinik' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <p>{{ $doctor->phone ?? '-' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $doctor->user->email ?? '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                {{ $doctor->sip_number ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $doctor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $doctor->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <p>{{ $doctor->appointments_count }} appointment</p>
                                <p class="text-xs text-gray-500">
                                    {{ $doctor->schedules_count }} jadwal
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}"
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                       class="px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
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

        <div class="mt-6">
            {{ $doctors->links() }}
        </div>

    @else
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                <span class="text-2xl">🩺</span>
            </div>

            <p class="text-gray-600 font-semibold">
                Belum ada data dokter.
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Tambahkan dokter agar pasien dapat melakukan pendaftaran.
            </p>

            <a href="{{ route('admin.doctors.create') }}"
               class="inline-block mt-5 px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700">
                Tambah Dokter
            </a>
        </div>
    @endif
</div>

@endsection