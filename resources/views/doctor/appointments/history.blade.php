@extends('layouts.doctor')

@section('title', 'Riwayat Tindakan')

@section('content')

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-emerald-700">
                Riwayat Tindakan
            </h3>

            <p class="text-sm text-gray-500">
                Daftar pasien yang tindakannya sudah selesai.
            </p>
        </div>

        @if ($appointments->count() > 0)

            <div class="md:hidden space-y-4">
                @foreach ($appointments as $appointment)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">
                        <h4 class="font-bold text-emerald-700">
                            {{ $appointment->patient->child_name ?? '-' }}
                        </h4>

                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Layanan</span>
                                <span class="font-semibold text-right">{{ $appointment->service->name ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="font-semibold text-right">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('d-m-Y') : '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Status</span>
                                <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                    Selesai
                                </span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                class="block text-center px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Pasien</th>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ $appointment->patient->child_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->patient->phone ?? '-' }}</p>
                                </td>

                                <td class="px-4 py-3">{{ $appointment->service->name ?? '-' }}</td>

                                <td class="px-4 py-3">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('d-m-Y') : '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                        Selesai
                                    </span>
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
                <p class="text-gray-500">
                    Belum ada riwayat tindakan.
                </p>
            </div>

        @endif

    </div>

@endsection