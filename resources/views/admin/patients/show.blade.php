@extends('layouts.admin')

@section('title', 'Detail Pasien')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
        <h3 class="text-xl font-bold text-emerald-700">Detail Pasien</h3>
        <p class="text-sm text-gray-500">
            Data lengkap pasien dan riwayat pendaftarannya.
        </p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.patients.edit', $patient) }}"
           class="px-5 py-3 bg-amber-400 text-gray-900 rounded-lg font-semibold hover:bg-amber-500">
            Edit
        </a>

        <a href="{{ route('admin.patients.index') }}"
           class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Kembali
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-1">
        <h4 class="font-bold text-emerald-700 mb-4">Identitas Pasien</h4>

        <div class="space-y-3 text-sm">
            <p>
                <span class="text-gray-500">Nama Anak:</span><br>
                <strong>{{ $patient->child_name }}</strong>
            </p>

            <p>
                <span class="text-gray-500">Umur/Berat:</span><br>
                {{ $patient->child_age }} tahun / {{ $patient->child_weight }} kg
            </p>

            <p>
                <span class="text-gray-500">Ayah:</span><br>
                {{ $patient->father_name }}
            </p>

            <p>
                <span class="text-gray-500">Ibu:</span><br>
                {{ $patient->mother_name }}
            </p>

            <p>
                <span class="text-gray-500">HP:</span><br>
                {{ $patient->phone }}
            </p>

            <p>
                <span class="text-gray-500">Akun:</span><br>
                {{ $patient->user->name ?? '-' }}<br>
                {{ $patient->user->email ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Alamat:</span><br>
                {{ $patient->address }}
            </p>

            <p>
                <span class="text-gray-500">Alergi Obat:</span><br>
                {{ $patient->drug_allergy ?: '-' }}
            </p>

            <p>
                <span class="text-gray-500">Riwayat Perdarahan:</span><br>
                {{ $patient->bleeding_history ?: '-' }}
            </p>

            <p>
                <span class="text-gray-500">Riwayat Operasi:</span><br>
                {{ $patient->surgery_history ?: '-' }}
            </p>

            <p>
                <span class="text-gray-500">Riwayat Penyakit:</span><br>
                {{ $patient->disease_history ?: '-' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
        <h4 class="font-bold text-emerald-700 mb-4">Riwayat Pendaftaran</h4>

        @if ($patient->appointments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Pembayaran</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($patient->appointments as $appointment)
                            <tr class="border-b">
                                <td class="px-4 py-3">
                                    {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                                    <span class="text-xs text-gray-500">
                                        {{ $appointment->appointment_time }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $appointment->service->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst($appointment->status) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst($appointment->payment->status ?? '-') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 bg-gray-50 rounded-xl text-gray-500">
                Belum ada riwayat pendaftaran.
            </div>
        @endif
    </div>
</div>

@endsection