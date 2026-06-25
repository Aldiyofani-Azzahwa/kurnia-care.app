@extends('layouts.doctor')

@section('title', 'Detail Jadwal Pasien')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-emerald-700">
                        Detail Jadwal Pasien
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Nomor Pendaftaran: #{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <a href="{{ route('doctor.appointments.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
                    Kembali
                </a>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Status Pendaftaran</p>
                <p class="mt-2 font-bold text-blue-700">
                    {{ ucfirst($appointment->status) }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                <p class="text-sm text-gray-500">Status Pembayaran</p>
                <p class="mt-2 font-bold text-emerald-700">
                    {{ ucfirst($appointment->payment->status ?? '-') }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-400">
                <p class="text-sm text-gray-500">Jadwal</p>
                <p class="mt-2 font-bold text-gray-800">
                    @if ($appointment->appointment_date)
                        {{ $appointment->appointment_date->format('d-m-Y') }}
                    @else
                        -
                    @endif
                    | {{ $appointment->appointment_time ?? '-' }}
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
                        {{ $appointment->patient->address ?? '-' }}
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

        {{-- DATA KHITAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Data Khitan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Layanan</p>
                    <p class="font-semibold">{{ $appointment->service->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Dokter</p>
                    <p class="font-semibold">{{ $appointment->doctor->name ?? '-' }}</p>
                </div>

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
                    <p class="text-gray-500">Jenis Obat</p>
                    <p class="font-semibold">
                        {{ $appointment->medicine_type ? ucfirst($appointment->medicine_type) : '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Metode Paket Khitan</p>
                    <p class="font-semibold">{{ $appointment->circumcision_package ?? '-' }}</p>
                </div>

            </div>
        </div>

        {{-- CATATAN TINDAKAN --}}
        {{-- CATATAN TINDAKAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Catatan Tindakan Dokter
            </h3>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
                    <strong>Catatan gagal disimpan.</strong>

                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM CATATAN --}}
            @if ($appointment->status === 'dikonfirmasi')
                <form action="{{ route('doctor.medical-notes.store', $appointment) }}" method="POST" class="space-y-4 mb-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Status Tindakan
                        </label>

                        <select name="action_status"
                            class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                            <option value="">Pilih Status Tindakan</option>
                            <option value="berhasil" @selected(old('action_status') === 'berhasil')>
                                Berhasil
                            </option>
                            <option value="perlu_kontrol" @selected(old('action_status') === 'perlu_kontrol')>
                                Perlu Kontrol
                            </option>
                            <option value="gagal" @selected(old('action_status') === 'gagal')>
                                Gagal
                            </option>
                            <option value="lainnya" @selected(old('action_status') === 'lainnya')>
                                Lainnya
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Catatan Tindakan
                        </label>

                        <textarea name="note" rows="4"
                            placeholder="Contoh: Tindakan berjalan lancar, pasien stabil, obat diberikan sesuai paket."
                            class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 md:justify-end">
                        <button type="submit" onclick="return confirm('Yakin ingin menyimpan catatan tindakan ini?')"
                            class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            @elseif ($appointment->status === 'selesai')
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                    <p class="text-emerald-700 font-semibold">
                        Tindakan sudah selesai.
                    </p>

                    <p class="text-sm text-gray-600 mt-1">
                        Catatan tindakan sudah tercatat. Appointment ini sudah berstatus selesai.
                    </p>
                </div>
            @else
                <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-amber-700 font-semibold">
                        Catatan belum bisa dibuat.
                    </p>

                    <p class="text-sm text-gray-600 mt-1">
                        Catatan tindakan hanya bisa dibuat saat status pendaftaran sudah dikonfirmasi.
                    </p>
                </div>
            @endif

            {{-- RIWAYAT CATATAN --}}
            <h4 class="font-semibold text-gray-700 mb-3">
                Riwayat Catatan
            </h4>

            @if ($appointment->medicalNotes->count() > 0)
                <div class="space-y-3">
                    @foreach ($appointment->medicalNotes as $note)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <p class="text-sm text-gray-500">
                                    Status:
                                    <span class="font-semibold text-emerald-700">
                                        {{ str_replace('_', ' ', ucfirst($note->action_status)) }}
                                    </span>
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ $note->created_at->format('d-m-Y H:i') }}
                                </p>
                            </div>

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