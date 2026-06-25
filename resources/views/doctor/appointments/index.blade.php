@extends('layouts.doctor')

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

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-emerald-700">
                Jadwal Pasien
            </h3>

            <p class="text-sm text-gray-500">
                Daftar pasien yang pembayarannya sudah Diterima dan siap ditangani.
            </p>
        </div>

        @if ($appointments->count() > 0)

            {{-- MOBILE CARD --}}
            <div class="md:hidden space-y-4">
                @foreach ($appointments as $appointment)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">

                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <h4 class="font-bold text-emerald-700 truncate">
                                    {{ $appointment->patient->child_name ?? '-' }}
                                </h4>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $appointment->patient->phone ?? '-' }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if ($appointment->status === 'dikonfirmasi')
                                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                        Dikonfirmasi
                                    </span>
                                @elseif ($appointment->status === 'selesai')
                                    <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4 border-t pt-3">
                                <span class="text-gray-500">Layanan</span>
                                <span class="font-semibold text-right">
                                    {{ $appointment->service->name ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="font-semibold text-right">
                                    @if ($appointment->appointment_date)
                                        {{ $appointment->appointment_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Hari</span>
                                <span class="font-semibold text-right">
                                    {{ $appointment->appointment_day ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Jam</span>
                                <span class="font-semibold text-right">
                                    {{ $appointment->appointment_time ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Umur / BB</span>
                                <span class="font-semibold text-right">
                                    {{ $appointment->patient->child_age ?? '-' }} tahun /
                                    {{ $appointment->patient->child_weight ?? '-' }} kg
                                </span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                class="block w-full text-center px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Pasien</th>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Jam</th>
                            <th class="px-4 py-3 text-left">Umur / BB</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold">
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
                                    @if ($appointment->appointment_date)
                                        {{ $appointment->appointment_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    {{ $appointment->appointment_time ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $appointment->patient->child_age ?? '-' }} tahun /
                                    {{ $appointment->patient->child_weight ?? '-' }} kg
                                </td>

                                <td class="px-4 py-3">
                                    @if ($appointment->status === 'dikonfirmasi')
                                        <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                            dikonfirmasi
                                        </span>
                                    @elseif ($appointment->status === 'selesai')
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                        class="inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">
                    Belum ada jadwal pasien.
                </p>

                <p class="text-sm text-gray-400">
                    Jadwal pasien akan muncul setelah pembayaran Diterima admin.
                </p>
            </div>

        @endif

    </div>

@endsection