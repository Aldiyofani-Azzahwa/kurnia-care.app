@extends('layouts.admin')

@section('title', 'Detail Dokter')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
        <h3 class="text-xl font-bold text-emerald-700">
            Detail Dokter
        </h3>

        <p class="text-sm text-gray-500">
            Informasi lengkap dokter dan riwayat appointment.
        </p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.doctors.edit', $doctor) }}"
           class="px-5 py-3 bg-amber-400 text-gray-900 rounded-lg font-semibold hover:bg-amber-500">
            Edit
        </a>

        <a href="{{ route('admin.doctors.index') }}"
           class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- PROFIL DOKTER --}}
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-1">
        <div class="text-center">
            @if ($doctor->photo)
                <img src="{{ asset('storage/' . $doctor->photo) }}"
                     alt="{{ $doctor->name }}"
                     class="w-32 h-32 rounded-2xl object-cover border mx-auto">
            @else
                <div class="w-32 h-32 rounded-2xl bg-emerald-100 flex items-center justify-center text-5xl font-bold text-emerald-700 mx-auto">
                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                </div>
            @endif

            <h4 class="mt-4 text-xl font-bold text-gray-900">
                {{ $doctor->name }}
            </h4>

            <p class="text-sm text-gray-500">
                {{ $doctor->specialist ?? 'Dokter Klinik' }}
            </p>

            <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-semibold
                {{ $doctor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $doctor->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>

        <div class="mt-6 space-y-3 text-sm">
            <p>
                <span class="text-gray-500">Email Login:</span><br>
                <strong>{{ $doctor->user->email ?? '-' }}</strong>
            </p>

            <p>
                <span class="text-gray-500">No HP:</span><br>
                <strong>{{ $doctor->phone ?? '-' }}</strong>
            </p>

            <p>
                <span class="text-gray-500">Nomor SIP:</span><br>
                <strong>{{ $doctor->sip_number ?? '-' }}</strong>
            </p>

            <p>
                <span class="text-gray-500">Bio:</span><br>
                {{ $doctor->bio ?: '-' }}
            </p>
        </div>
    </div>

    {{-- RIWAYAT APPOINTMENT --}}
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
        <h4 class="font-bold text-emerald-700 mb-4">
            Riwayat Appointment
        </h4>

        @if ($doctor->appointments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Pasien</th>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($doctor->appointments->sortByDesc('created_at')->take(10) as $appointment)
                            <tr class="border-b">
                                <td class="px-4 py-3">
                                    {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                                    <span class="text-xs text-gray-500">
                                        {{ $appointment->appointment_time }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $appointment->patient->child_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $appointment->service->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 bg-gray-50 rounded-xl text-gray-500">
                Dokter belum memiliki riwayat appointment.
            </div>
        @endif
    </div>
</div>

@endsection