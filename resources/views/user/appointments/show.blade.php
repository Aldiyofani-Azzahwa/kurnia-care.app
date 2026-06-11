@extends('layouts.user')

@section('title', 'Detail Pendaftaran')

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

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-emerald-700">
                        Detail Pendaftaran Khitan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Nomor Pendaftaran: #{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <a href="{{ route('user.appointments.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
                    Kembali
                </a>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 
                {{ $appointment->status === 'batal' ? 'border-red-500' : 'border-emerald-500' }}">
                <p class="text-sm text-gray-500">Status Pendaftaran</p>

                @if ($appointment->status === 'menunggu')
                    <p class="mt-2 font-bold text-amber-600">Menunggu</p>
                @elseif ($appointment->status === 'diproses')
                    <p class="mt-2 font-bold text-blue-600">Diproses</p>
                @elseif ($appointment->status === 'selesai')
                    <p class="mt-2 font-bold text-emerald-700">Selesai</p>
                @elseif ($appointment->status === 'batal')
                    <p class="mt-2 font-bold text-red-600">Batal</p>
                @else
                    <p class="mt-2 font-bold text-gray-700">{{ ucfirst($appointment->status) }}</p>
                @endif
            </div>

            <div
                class="bg-white rounded-xl shadow-sm p-5 border-l-4 
                {{ $appointment->payment && $appointment->payment->status === 'ditolak' ? 'border-red-500' : 'border-amber-400' }}">
                <p class="text-sm text-gray-500">Status Pembayaran</p>

                @if ($appointment->payment && $appointment->payment->status === 'pending')
                    <p class="mt-2 font-bold text-amber-600">Pending</p>
                @elseif ($appointment->payment && $appointment->payment->status === 'diverifikasi')
                    <p class="mt-2 font-bold text-emerald-700">Diverifikasi</p>
                @elseif ($appointment->payment && $appointment->payment->status === 'ditolak')
                    <p class="mt-2 font-bold text-red-600">Ditolak</p>
                @else
                    <p class="mt-2 font-bold text-gray-600">Belum Ada</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                <p class="mt-2 font-bold text-emerald-700">
                    Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                </p>
            </div>

        </div>

        {{-- DATA ANAK --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Identitas Anak
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Nama Anak</p>
                    <p class="font-semibold">{{ $appointment->patient->child_name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Umur</p>
                    <p class="font-semibold">{{ $appointment->patient->child_age ?? '-' }} tahun</p>
                </div>

                <div>
                    <p class="text-gray-500">Berat Badan</p>
                    <p class="font-semibold">{{ $appointment->patient->child_weight ?? '-' }} kg</p>
                </div>

                <div>
                    <p class="text-gray-500">No HP / WA</p>
                    <p class="font-semibold">{{ $appointment->patient->phone ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Alamat</p>
                    <p class="font-semibold">
                        @if ($appointment->patient && $appointment->patient->address)
                            {{ $appointment->patient->address }}
                        @else
                            {{ $appointment->patient->village_name ?? '-' }},
                            {{ $appointment->patient->district_name ?? '-' }},
                            {{ $appointment->patient->city_name ?? '-' }},
                            {{ $appointment->patient->province_name ?? '-' }}
                        @endif
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Alergi Obat</p>
                    <p class="font-semibold">{{ $appointment->patient->drug_allergy ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Riwayat Perdarahan</p>
                    <p class="font-semibold">{{ $appointment->patient->bleeding_history ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Riwayat Operasi</p>
                    <p class="font-semibold">{{ $appointment->patient->surgery_history ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Riwayat Penyakit</p>
                    <p class="font-semibold">{{ $appointment->patient->disease_history ?? '-' }}</p>
                </div>

            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Identitas Orang Tua
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Nama Ayah</p>
                    <p class="font-semibold">{{ $appointment->patient->father_name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Nama Ibu</p>
                    <p class="font-semibold">{{ $appointment->patient->mother_name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Instagram</p>
                    <p class="font-semibold">{{ $appointment->patient->instagram ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Facebook</p>
                    <p class="font-semibold">{{ $appointment->patient->facebook ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Sumber Informasi</p>
                    <p class="font-semibold">{{ $appointment->patient->information_source ?? '-' }}</p>
                </div>

            </div>
        </div>

        {{-- DATA KHITAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Data Khitan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Tanggal Khitan</p>
                    <p class="font-semibold">
                        @if ($appointment->appointment_date)
                            {{ $appointment->appointment_date->format('d-m-Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Hari</p>
                    <p class="font-semibold">{{ $appointment->appointment_day ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jam</p>
                    <p class="font-semibold">{{ $appointment->appointment_time ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Dokter</p>
                    <p class="font-semibold">{{ $appointment->doctor->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Layanan</p>
                    <p class="font-semibold">{{ $appointment->service->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jenis Obat</p>
                    <p class="font-semibold">{{ $appointment->medicine_type ? ucfirst($appointment->medicine_type) : '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Metode Paket Khitan</p>
                    <p class="font-semibold">{{ $appointment->circumcision_package ?? '-' }}</p>
                </div>

            </div>
        </div>

        {{-- PEMBAYARAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Informasi Pembayaran
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Metode Pembayaran</p>
                    <p class="font-semibold">{{ $appointment->payment->payment_method ?? 'Transfer Bank' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Nominal</p>
                    <p class="font-semibold">
                        Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>

                    @if ($appointment->payment && $appointment->payment->status === 'pending')
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                            Pending
                        </span>
                    @elseif ($appointment->payment && $appointment->payment->status === 'diverifikasi')
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                            Diverifikasi
                        </span>
                    @elseif ($appointment->payment && $appointment->payment->status === 'ditolak')
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                            Ditolak
                        </span>
                    @else
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                            Belum Ada
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-gray-500">Bukti Pembayaran</p>

                    @if ($appointment->payment && $appointment->payment->proof_image)
                        <a href="{{ asset('storage/' . $appointment->payment->proof_image) }}" target="_blank"
                            class="text-emerald-700 font-semibold hover:underline">
                            Lihat Bukti
                        </a>
                    @else
                        <p class="font-semibold text-red-600">
                            Belum upload bukti pembayaran
                        </p>
                    @endif
                </div>

                @if ($appointment->payment && $appointment->payment->status === 'ditolak' && $appointment->payment->rejection_reason)
                    <div class="md:col-span-2 rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-sm text-gray-500">Alasan Penolakan</p>
                        <p class="font-semibold text-red-700 mt-1">
                            {{ $appointment->payment->rejection_reason }}
                        </p>
                    </div>
                @endif

            </div>

            <div class="mt-5">

                @if (!$appointment->payment)
                    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-red-700 font-semibold">
                            Data pembayaran tidak ditemukan.
                        </p>
                    </div>
                @elseif ($appointment->status === 'batal')
                    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-red-700 font-semibold">
                            Pendaftaran sudah dibatalkan. Pembayaran tidak bisa diupload.
                        </p>
                    </div>
                @elseif ($appointment->payment->proof_image)
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                        <p class="text-emerald-700 font-semibold">
                            Bukti pembayaran sudah dikirim.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Silakan tunggu verifikasi admin. Bukti pembayaran tidak bisa diupload ulang.
                        </p>
                    </div>
                @elseif ($appointment->payment->status !== 'pending')
                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                        <p class="text-amber-700 font-semibold">
                            Pembayaran sudah diproses dan tidak bisa diubah.
                        </p>
                    </div>
                @else
                    <a href="{{ route('user.payments.edit', $appointment) }}"
                        class="inline-block px-5 py-3 bg-amber-400 text-gray-900 font-semibold rounded-lg hover:bg-amber-500">
                        Upload Bukti Pembayaran
                    </a>
                @endif

            </div>
        </div>

        {{-- CATATAN TINDAKAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Catatan Tindakan Dokter
            </h3>

            @if ($appointment->medicalNotes->count() > 0)
                <div class="space-y-3">
                    @foreach ($appointment->medicalNotes as $note)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Status: {{ ucfirst($note->action_status) }}
                            </p>

                            <p class="mt-2 text-gray-700">
                                {{ $note->note }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">
                    Belum ada catatan tindakan dokter.
                </p>
            @endif
        </div>

    </div>

@endsection