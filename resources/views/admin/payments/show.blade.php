@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')

    @php
        $appointment = $payment->appointment;
        $patient = $appointment?->patient;
        $service = $appointment?->service;
        $doctor = $appointment?->doctor;

        $isAdminRegistration = $patient && (
            $patient->registration_type !== 'online'
            || !is_null($patient->registered_by_id)
            || is_null($patient->user_id)
        );

        $isPending = $payment->status === \App\Models\Payment::STATUS_PENDING;
        $isAccepted = $payment->status === \App\Models\Payment::STATUS_DITERIMA;
        $isRejected = $payment->status === \App\Models\Payment::STATUS_DITOLAK;

        $isAppointmentCompleted = $appointment?->status === \App\Models\Appointment::STATUS_SELESAI;
        $isAppointmentCancelled = $appointment?->status === \App\Models\Appointment::STATUS_DIBATALKAN;

        $canAdminUploadProof = $isAdminRegistration
            && !$isAccepted
            && !$isAppointmentCompleted
            && !$isAppointmentCancelled
            && in_array($payment->status, [
                \App\Models\Payment::STATUS_PENDING,
                \App\Models\Payment::STATUS_DITOLAK,
            ], true);

        $showAdminUploadForm = $canAdminUploadProof && (!$payment->proof_image || $isRejected);

        $paymentStatusLabel = match ($payment->status) {
            \App\Models\Payment::STATUS_PENDING => 'Pending',
            \App\Models\Payment::STATUS_DITERIMA => 'Diterima',
            \App\Models\Payment::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($payment->status ?? '-'),
        };

        $paymentBorderClass = match ($payment->status) {
            \App\Models\Payment::STATUS_DITERIMA => 'border-emerald-500',
            \App\Models\Payment::STATUS_DITOLAK => 'border-red-500',
            \App\Models\Payment::STATUS_PENDING => 'border-amber-400',
            default => 'border-gray-400',
        };

        $paymentTextClass = match ($payment->status) {
            \App\Models\Payment::STATUS_DITERIMA => 'text-emerald-700',
            \App\Models\Payment::STATUS_DITOLAK => 'text-red-600',
            \App\Models\Payment::STATUS_PENDING => 'text-amber-600',
            default => 'text-gray-600',
        };

        $appointmentStatusLabel = match ($appointment?->status) {
            \App\Models\Appointment::STATUS_MENUNGGU => 'Menunggu',
            \App\Models\Appointment::STATUS_DIKONFIRMASI => 'Dikonfirmasi',
            \App\Models\Appointment::STATUS_SELESAI => 'Selesai',
            \App\Models\Appointment::STATUS_DIBATALKAN => 'Dibatalkan',
            default => $appointment?->status ? ucfirst($appointment->status) : '-',
        };

        $appointmentBorderClass = match ($appointment?->status) {
            \App\Models\Appointment::STATUS_MENUNGGU => 'border-amber-400',
            \App\Models\Appointment::STATUS_DIKONFIRMASI => 'border-blue-500',
            \App\Models\Appointment::STATUS_SELESAI => 'border-emerald-500',
            \App\Models\Appointment::STATUS_DIBATALKAN => 'border-red-500',
            default => 'border-gray-400',
        };

        $appointmentTextClass = match ($appointment?->status) {
            \App\Models\Appointment::STATUS_MENUNGGU => 'text-amber-600',
            \App\Models\Appointment::STATUS_DIKONFIRMASI => 'text-blue-600',
            \App\Models\Appointment::STATUS_SELESAI => 'text-emerald-700',
            \App\Models\Appointment::STATUS_DIBATALKAN => 'text-red-600',
            default => 'text-gray-600',
        };
    @endphp

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

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
            <strong>Data belum lengkap.</strong>

            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-emerald-700">
                        Detail Pembayaran
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Nomor Pembayaran: #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <a href="{{ route('admin.payments.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
                    Kembali
                </a>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 {{ $paymentBorderClass }}">
                <p class="text-sm text-gray-500">Status Pembayaran</p>

                <p class="mt-2 font-bold {{ $paymentTextClass }}">
                    {{ $paymentStatusLabel }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-400">
                <p class="text-sm text-gray-500">Nominal</p>

                <p class="mt-2 font-bold text-emerald-700">
                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 {{ $appointmentBorderClass }}">
                <p class="text-sm text-gray-500">Status Pendaftaran</p>

                <p class="mt-2 font-bold {{ $appointmentTextClass }}">
                    {{ $appointmentStatusLabel }}
                </p>
            </div>

        </div>

        {{-- DATA PASIEN DAN APPOINTMENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- DATA PASIEN --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                    Data Pasien
                </h3>

                @include('partials.patient-child-photo', ['patient' => $patient])

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nama Anak</p>
                        <p class="font-semibold">
                            {{ $patient->child_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Umur</p>
                        <p class="font-semibold">
                            {{ $patient->child_age ?? '-' }} tahun
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Berat Badan</p>
                        <p class="font-semibold">
                            {{ $patient->child_weight ?? '-' }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Nama Ayah</p>
                        <p class="font-semibold">
                            {{ $patient->father_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Nama Ibu</p>
                        <p class="font-semibold">
                            {{ $patient->mother_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">No HP / WA</p>
                        <p class="font-semibold">
                            {{ $patient->phone ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Alamat</p>
                        <p class="font-semibold">
                            {{ $patient->address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- DATA KHITAN --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                    Data Khitan
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Layanan</p>
                        <p class="font-semibold">
                            {{ $service->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Dokter</p>
                        <p class="font-semibold">
                            {{ $doctor->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Tanggal Khitan</p>
                        <p class="font-semibold">
                            @if ($appointment && $appointment->appointment_date)
                                {{ $appointment->appointment_date->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Hari</p>
                        <p class="font-semibold">
                            {{ $appointment->appointment_day ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jam</p>
                        <p class="font-semibold">
                            {{ $appointment->appointment_time ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jenis Obat</p>
                        <p class="font-semibold">
                            {{ $appointment && $appointment->medicine_type ? ucfirst($appointment->medicine_type) : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Paket Khitan</p>
                        <p class="font-semibold">
                            {{ $appointment->circumcision_package ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- BUKTI PEMBAYARAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Bukti Pembayaran DP
            </h3>

            @if ($payment->proof_image)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div>
                        <p class="text-sm text-gray-500 mb-3">
                            Klik gambar untuk membuka ukuran penuh.
                        </p>

                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti Pembayaran DP"
                                class="w-full max-h-[500px] object-contain rounded-xl border border-gray-300 bg-gray-50">
                        </a>
                    </div>

                    <div class="space-y-4">

                        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                            <p class="text-sm text-gray-500">Metode Pembayaran</p>

                            <p class="font-semibold">
                                {{ $payment->payment_method ?? 'Transfer Bank' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                            <p class="text-sm text-gray-500">Nominal yang Harus Dibayar</p>

                            <p class="font-bold text-lg text-emerald-700">
                                Rp{{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Waktu Upload / Update</p>

                            <p class="font-semibold">
                                {{ $payment->updated_at ? $payment->updated_at->format('d-m-Y H:i') : '-' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Ditangani Oleh</p>

                            <p class="font-semibold">
                                {{ $payment->verifier->name ?? '-' }}
                            </p>

                            @if ($payment->verified_at)
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $payment->verified_at->format('d-m-Y H:i') }}
                                </p>
                            @endif
                        </div>

                    </div>

                </div>
            @else
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-red-700 font-medium">
                        Bukti pembayaran belum tersedia.
                    </p>

                    @if ($isAdminRegistration)
                        <p class="text-sm text-gray-500 mt-1">
                            Pendaftaran ini dibuat oleh admin. Admin dapat mengupload bukti pembayaran pada form di bawah.
                        </p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">
                            Pendaftaran ini dibuat oleh pasien online. Silakan tunggu pasien mengupload bukti pembayaran.
                        </p>
                    @endif
                </div>
            @endif

            @if ($showAdminUploadForm)
                <form action="{{ route('admin.payments.upload-proof', $payment) }}" method="POST" enctype="multipart/form-data"
                    class="mt-5 rounded-xl border border-gray-200 bg-gray-50 p-5">
                    @csrf

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $payment->proof_image ? 'Upload Ulang Bukti DP DP' : 'Upload Bukti Pembayaran DP DP' }}
                    </label>

                    <input type="file" name="proof_image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm">

                    <p class="text-xs text-gray-500 mt-2">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    @error('proof_image')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <button type="submit"
                        class="mt-4 px-5 py-3 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                        {{ $payment->proof_image ? 'Upload Ulang Bukti' : 'Upload Bukti Pembayaran DP DP' }}
                    </button>
                </form>
            @endif
        </div>

        {{-- ALASAN PENOLAKAN --}}
        @if ($isRejected && $payment->rejection_reason)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-3">
                    Alasan Penolakan
                </h3>

                <p class="text-gray-700">
                    {{ $payment->rejection_reason }}
                </p>
            </div>
        @endif

        {{-- AKSI ADMIN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Aksi Admin
            </h3>

            @if ($isAccepted)
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                    <p class="text-emerald-700 font-medium">
                        Pembayaran ini sudah diterima.
                    </p>

                    @if ($payment->verified_at)
                        <p class="text-sm text-gray-500 mt-1">
                            Diterima pada {{ $payment->verified_at->format('d-m-Y H:i') }}
                        </p>
                    @endif
                </div>

            @elseif ($isRejected)
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-red-700 font-medium">
                        Pembayaran ini ditolak.
                    </p>

                    @if ($isAdminRegistration)
                        <p class="text-sm text-gray-500 mt-1">
                            Karena pendaftaran dibuat oleh admin, admin dapat mengupload ulang bukti pembayaran pada form di atas.
                        </p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">
                            Pasien masih dapat mengunggah ulang bukti pembayaran melalui dashboard pasien.
                        </p>
                    @endif
                </div>

            @elseif ($isAppointmentCancelled)
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-red-700 font-medium">
                        Appointment sudah dibatalkan dan pembayaran tidak bisa diubah.
                    </p>
                </div>

            @elseif ($isAppointmentCompleted)
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                    <p class="text-emerald-700 font-medium">
                        Appointment sudah selesai dan pembayaran tidak bisa diubah.
                    </p>
                </div>

            @elseif (!$payment->proof_image)
                <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-amber-700 font-medium">
                        Bukti pembayaran belum diupload.
                    </p>

                    @if ($isAdminRegistration)
                        <p class="text-sm text-gray-500 mt-1">
                            Upload bukti pembayaran terlebih dahulu pada form di atas, lalu klik Terima Pembayaran.
                        </p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">
                            Tunggu pasien mengupload bukti pembayaran melalui dashboard pasien.
                        </p>
                    @endif
                </div>

            @elseif (!$isPending)
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                    <p class="text-gray-700 font-medium">
                        Status pembayaran tidak dapat diubah.
                    </p>
                </div>

            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- TERIMA --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                        <h4 class="font-semibold text-emerald-700 mb-2">
                            Terima Pembayaran
                        </h4>

                        <p class="text-sm text-gray-600 mb-4">
                            Klik tombol terima jika bukti transfer sudah sesuai.
                        </p>

                        <form method="POST" action="{{ route('admin.payments.accept', $payment) }}">
                            @csrf

                            <button type="submit"
                                onclick="return confirm('Yakin ingin menerima pembayaran ini? Appointment akan dikonfirmasi.')"
                                class="w-full px-5 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Terima Pembayaran
                            </button>
                        </form>
                    </div>

                    {{-- TOLAK --}}
                    <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                        <h4 class="font-semibold text-red-700 mb-2">
                            Tolak Pembayaran
                        </h4>

                        <p class="text-sm text-gray-600 mb-4">
                            Isi alasan penolakan jika bukti transfer tidak valid.
                        </p>

                        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="space-y-3">
                            @csrf

                            <textarea name="rejection_reason" rows="3" required
                                placeholder="Contoh: Nominal tidak sesuai / bukti tidak jelas"
                                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none">{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <p class="text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button type="submit"
                                onclick="return confirm('Yakin ingin menolak pembayaran ini? Pasien masih dapat upload ulang bukti pembayaran.')"
                                class="w-full px-5 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Tolak Pembayaran
                            </button>
                        </form>
                    </div>

                </div>
            @endif
        </div>

    </div>

@endsection