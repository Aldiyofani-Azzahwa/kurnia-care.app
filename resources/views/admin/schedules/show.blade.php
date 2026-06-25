@extends('layouts.admin')

@section('title', 'Detail Jadwal Pasien')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
        <h3 class="text-xl font-bold text-emerald-700">
            Detail Jadwal Pasien
        </h3>

        <p class="text-sm text-gray-500">
            Informasi lengkap pasien, layanan, dokter, dan pembayaran.
        </p>
    </div>

    <a href="{{ route('admin.schedules.index', ['date' => $appointment->appointment_date?->format('Y-m-d')]) }}"
       class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
        Kembali
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- DATA PASIEN --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-bold text-emerald-700 mb-4">
            Data Pasien
        </h4>

        <div class="space-y-3 text-sm">
            <p>
                <span class="text-gray-500">Nama Anak:</span><br>
                <strong>{{ $appointment->patient->child_name ?? '-' }}</strong>
            </p>

            <p>
                <span class="text-gray-500">Umur / Berat:</span><br>
                {{ $appointment->patient->child_age ?? '-' }} tahun /
                {{ $appointment->patient->child_weight ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Orang Tua:</span><br>
                Ayah: {{ $appointment->patient->father_name ?? '-' }}<br>
                Ibu: {{ $appointment->patient->mother_name ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">No HP:</span><br>
                {{ $appointment->patient->phone ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Alamat:</span><br>
                {{ $appointment->patient->village_name ?? '-' }},
                {{ $appointment->patient->district_name ?? '-' }},
                {{ $appointment->patient->city_name ?? '-' }},
                {{ $appointment->patient->province_name ?? '-' }}
            </p>
        </div>
    </div>

    {{-- DETAIL JADWAL --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-bold text-emerald-700 mb-4">
            Jadwal Tindakan
        </h4>

        <div class="space-y-3 text-sm">
            <p>
                <span class="text-gray-500">Tanggal:</span><br>
                <strong>
                    {{ $appointment->appointment_date?->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </strong>
            </p>

            <p>
                <span class="text-gray-500">Jam:</span><br>
                {{ substr($appointment->appointment_time, 0, 5) }}
            </p>

            <p>
                <span class="text-gray-500">Dokter:</span><br>
                {{ $appointment->doctor->name ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Layanan:</span><br>
                {{ $appointment->service->name ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Jenis Obat:</span><br>
                {{ ucfirst($appointment->medicine_type ?? '-') }}
            </p>

            <form action="{{ route('admin.schedules.updateStatus', $appointment) }}"
                  method="POST"
                  class="pt-2">
                @csrf
                @method('PATCH')

                <label class="block text-gray-500 mb-1">
                    Status Tindakan:
                </label>

                <select name="status"
                        onchange="this.form.submit()"
                        class="w-full h-12 rounded-lg border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                    <option value="menunggu" @selected($appointment->status === 'menunggu')>Menunggu</option>
                    <option value="dikonfirmasi" @selected($appointment->status === 'dikonfirmasi')>Dikonfirmasi</option>
                    <option value="selesai" @selected($appointment->status === 'selesai')>Selesai</option>
                    <option value="dibatalkan" @selected($appointment->status === 'dibatalkan')>Dibatalkan</option>
                </select>
            </form>
        </div>
    </div>

    {{-- PEMBAYARAN --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h4 class="font-bold text-emerald-700 mb-4">
            Pembayaran
        </h4>

        <div class="space-y-3 text-sm">
            <p>
                <span class="text-gray-500">Metode:</span><br>
                {{ $appointment->payment->payment_method ?? '-' }}
            </p>

            <p>
                <span class="text-gray-500">Nominal:</span><br>
                <strong>
                    Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                </strong>
            </p>

            <p>
                <span class="text-gray-500">Status Pembayaran:</span><br>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold
                    @if (($appointment->payment->status ?? '') === 'diterima')
                        bg-green-100 text-green-700
                    @elseif (($appointment->payment->status ?? '') === 'ditolak')
                        bg-red-100 text-red-700
                    @else
                        bg-amber-100 text-amber-700
                    @endif">
                    {{ ucfirst($appointment->payment->status ?? 'pending') }}
                </span>
            </p>

            @if ($appointment->payment?->proof_image)
                <div>
                    <span class="text-gray-500">Bukti Transfer:</span>

                    <img src="{{ asset('storage/' . $appointment->payment->proof_image) }}"
                         alt="Bukti Transfer"
                         class="mt-2 rounded-xl border w-full">
                </div>
            @else
                <div class="rounded-xl bg-gray-50 p-4 text-gray-500">
                    Belum ada bukti pembayaran.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection