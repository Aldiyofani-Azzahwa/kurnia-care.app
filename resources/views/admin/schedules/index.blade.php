@extends('layouts.admin')

@section('title', 'Jadwal Pasien')

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
        <p class="text-xs text-gray-500">Total Jadwal</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">
            {{ $totalToday }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-400">
        <p class="text-xs text-gray-500">Menunggu</p>
        <h3 class="text-3xl font-bold text-amber-500 mt-2">
            {{ $waitingCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Diproses</p>
        <h3 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $processCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Selesai</p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">
            {{ $doneCount }}
        </h3>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-emerald-700">
                Jadwal Pasien
            </h3>

            <p class="text-sm text-gray-500">
                Daftar pasien yang memiliki jadwal tindakan pada tanggal yang dipilih.
            </p>
        </div>

        <div class="px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-sm">
            {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET"
          action="{{ route('admin.schedules.index') }}"
          class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">

        <input type="date"
               name="date"
               value="{{ $selectedDate }}"
               class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Cari pasien, dokter, layanan..."
               class="md:col-span-2 w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

        <select name="status"
                class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="">Semua Status</option>
            <option value="menunggu" @selected($status === 'menunggu')>Menunggu</option>
            <option value="diproses" @selected($status === 'diproses')>Diproses</option>
            <option value="selesai" @selected($status === 'selesai')>Selesai</option>
            <option value="batal" @selected($status === 'batal')>Batal</option>
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-5 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700">
                Cari
            </button>

            <a href="{{ route('admin.schedules.index') }}"
               class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl text-center hover:bg-gray-300">
                Reset
            </a>
        </div>
    </form>

    @if ($appointments->count() > 0)

        {{-- MOBILE CARD --}}
        <div class="md:hidden space-y-4">
            @foreach ($appointments as $appointment)
                <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-4 shadow-sm">

                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-gray-900 text-base">
                                {{ $appointment->patient->child_name ?? '-' }}
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $appointment->service->name ?? '-' }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if ($appointment->status === 'selesai')
                                bg-green-100 text-green-700
                            @elseif ($appointment->status === 'diproses')
                                bg-blue-100 text-blue-700
                            @elseif ($appointment->status === 'batal')
                                bg-red-100 text-red-700
                            @else
                                bg-amber-100 text-amber-700
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">Jam</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ substr($appointment->appointment_time, 0, 5) }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">Dokter</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $appointment->doctor->name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">No HP</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $appointment->patient->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3 border border-emerald-50">
                            <p class="text-gray-500">Pembayaran</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ ucfirst($appointment->payment->status ?? '-') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.schedules.show', $appointment) }}"
                           class="py-2 text-center bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                            Detail
                        </a>

                        <form action="{{ route('admin.schedules.updateStatus', $appointment) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="w-full py-2 px-2 bg-white border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                                <option value="menunggu" @selected($appointment->status === 'menunggu')>Menunggu</option>
                                <option value="diproses" @selected($appointment->status === 'diproses')>Diproses</option>
                                <option value="selesai" @selected($appointment->status === 'selesai')>Selesai</option>
                                <option value="batal" @selected($appointment->status === 'batal')>Batal</option>
                            </select>
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
                        <th class="px-4 py-3 text-left">Jam</th>
                        <th class="px-4 py-3 text-left">Pasien</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Dokter</th>
                        <th class="px-4 py-3 text-left">Pembayaran</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-semibold">
                                    {{ substr($appointment->appointment_time, 0, 5) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">
                                    {{ $appointment->patient->child_name ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $appointment->patient->phone ?? '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                {{ $appointment->service->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $appointment->doctor->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if (($appointment->payment->status ?? '') === 'diverifikasi')
                                        bg-green-100 text-green-700
                                    @elseif (($appointment->payment->status ?? '') === 'ditolak')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-amber-100 text-amber-700
                                    @endif">
                                    {{ ucfirst($appointment->payment->status ?? 'pending') }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <form action="{{ route('admin.schedules.updateStatus', $appointment) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status"
                                            onchange="this.form.submit()"
                                            class="h-10 rounded-lg border border-gray-300 px-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                                        <option value="menunggu" @selected($appointment->status === 'menunggu')>Menunggu</option>
                                        <option value="diproses" @selected($appointment->status === 'diproses')>Diproses</option>
                                        <option value="selesai" @selected($appointment->status === 'selesai')>Selesai</option>
                                        <option value="batal" @selected($appointment->status === 'batal')>Batal</option>
                                    </select>
                                </form>
                            </td>

                            <td class="px-4 py-3">
                                <a href="{{ route('admin.schedules.show', $appointment) }}"
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

    @else
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                <span class="text-2xl">📅</span>
            </div>

            <p class="text-gray-600 font-semibold">
                Tidak ada jadwal pasien pada tanggal ini.
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Coba pilih tanggal lain atau tunggu pasien melakukan pendaftaran.
            </p>
        </div>
    @endif
</div>

@endsection