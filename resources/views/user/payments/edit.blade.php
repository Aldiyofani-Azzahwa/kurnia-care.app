@extends('layouts.user')

@section('title', 'Upload Bukti Pembayaran DP DP')

@section('content')

    @php
        $patient = $appointment->patient;
        $service = $appointment->service;
        $payment = $appointment->payment;
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
            <strong>Upload gagal.</strong>

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
            <h3 class="text-xl font-bold text-emerald-700">
                Upload Bukti Pembayaran DP DP
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Silakan upload bukti transfer agar admin dapat memverifikasi pembayaran Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- DETAIL PENDAFTARAN --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-semibold text-emerald-700 mb-4">
                    Detail Pendaftaran
                </h4>

                <div class="space-y-3 text-sm">

                    <div>
                        <p class="text-gray-500">Nama Anak</p>
                        <p class="font-semibold">
                            {{ $patient->child_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Layanan</p>
                        <p class="font-semibold">
                            {{ $service->name ?? '-' }}
                        </p>
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
                        <p class="text-gray-500">Jam</p>
                        <p class="font-semibold">
                            {{ $appointment->appointment_time ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Nominal DP</p>
                        <p class="font-bold text-lg text-emerald-700">
                            Rp{{ number_format($appointment->payment->amount ?? config('payment.dp_amount', 100000), 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Status Pembayaran</p>

                        @if ($payment && $payment->status === 'pending')
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                                Pending
                            </span>
                        @elseif ($payment && $payment->status === 'diterima')
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                Diterima
                            </span>
                        @elseif ($payment && $payment->status === 'ditolak')
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                Belum Ada
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 space-y-3">
                <h4 class="font-semibold text-emerald-700">
                    Instruksi Pembayaran DP
                </h4>

                <div>
                    <p class="text-gray-500">Bank Tujuan</p>
                    <p class="font-bold text-gray-800">
                        {{ config('payment.bank_name') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Nomor Rekening</p>
                    <p class="font-bold text-lg text-emerald-700">
                        {{ config('payment.account_number') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Atas Nama</p>
                    <p class="font-bold text-gray-800">
                        {{ config('payment.account_holder') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Harga Paket</p>
                    <p class="font-semibold text-gray-800">
                        Rp{{ number_format($appointment->service->price ?? 0, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">DP Dibayar</p>
                    <p class="font-bold text-emerald-700">
                        Rp{{ number_format($appointment->payment->amount ?? config('payment.dp_amount', 100000), 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Sisa Pelunasan</p>
                    <p class="font-bold text-red-600">
                        Rp{{ number_format(max(($appointment->service->price ?? 0) - ($appointment->payment->amount ?? config('payment.dp_amount', 100000)), 0), 0, ',', '.') }}
                    </p>
                </div>

                <div class="pt-2 border-t border-emerald-200">
                    <p class="text-gray-700">
                        {{ config('payment.payment_note') }}
                    </p>
                </div>
            </div>

            {{-- FORM / INFO UPLOAD --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-semibold text-emerald-700 mb-4">
                    Form Upload Bukti
                </h4>

                @if (!$payment)

                    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-red-700 font-semibold">
                            Data pembayaran tidak ditemukan.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Silakan kembali ke detail pendaftaran atau hubungi admin.
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>

                @elseif ($appointment->status === 'dibatalkan')

                    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-red-700 font-semibold">
                            Pendaftaran sudah dibatalkan.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Pembayaran untuk pendaftaran ini tidak bisa diupload.
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>

                @elseif ($appointment->status === 'selesai')

                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                        <p class="text-emerald-700 font-semibold">
                            Pendaftaran sudah selesai.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Pembayaran untuk pendaftaran ini tidak bisa diubah.
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>

                @elseif ($payment->status === 'ditolak')

                    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-red-700 font-semibold">
                            Pembayaran ditolak. Silakan upload ulang bukti pembayaran.
                        </p>

                        @if ($payment->rejection_reason)
                            <p class="text-sm text-red-600 mt-1">
                                Alasan: {{ $payment->rejection_reason }}
                            </p>
                        @endif

                        @if ($payment->proof_image)
                            <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank"
                                class="inline-block mt-3 text-sm text-red-700 font-semibold hover:underline">
                                Lihat Bukti Lama
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('user.payments.update', $appointment) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4 mt-5">

                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Bukti Transfer Baru
                            </label>

                            <input type="file" name="proof_image" accept="image/png,image/jpeg,image/jpg"
                                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2">

                            <p class="text-xs text-gray-500 mt-1">
                                Format JPG/PNG, maksimal 5MB.
                            </p>

                            @error('proof_image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 md:justify-end">
                            <a href="{{ route('user.appointments.show', $appointment) }}"
                                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                Batal
                            </a>

                            <button type="submit"
                                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                                Upload Ulang
                            </button>
                        </div>
                    </form>

                @elseif ($payment->status === 'diterima')

                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                        <p class="text-emerald-700 font-semibold">
                            Pembayaran sudah diterima.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Pembayaran ini tidak bisa diubah atau diupload ulang.
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>

                @elseif ($payment->proof_image)

                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                        <p class="text-amber-700 font-semibold">
                            Bukti pembayaran sudah dikirim.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Silakan tunggu verifikasi dari admin.
                        </p>

                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank"
                            class="inline-block mt-3 text-sm text-amber-700 font-semibold hover:underline">
                            Lihat Bukti Pembayaran DP
                        </a>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali ke Detail Pendaftaran
                        </a>
                    </div>

                @elseif ($payment->status !== 'pending')

                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                        <p class="text-amber-700 font-semibold">
                            Pembayaran sudah dikonfirmasi.
                        </p>

                        <p class="text-sm text-gray-600 mt-1">
                            Pembayaran ini tidak bisa diubah atau diupload ulang.
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.appointments.show', $appointment) }}"
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>

                @else

                    <form action="{{ route('user.payments.update', $appointment) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4">

                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Bukti Transfer
                            </label>

                            <input type="file" name="proof_image" accept="image/png,image/jpeg,image/jpg"
                                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2">

                            <p class="text-xs text-gray-500 mt-1">
                                Format JPG/PNG, maksimal 5MB.
                            </p>

                            @error('proof_image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 md:justify-end">
                            <a href="{{ route('user.appointments.show', $appointment) }}"
                                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                Batal
                            </a>

                            <button type="submit"
                                class="px-5 py-3 bg-amber-400 text-gray-900 font-semibold rounded-lg hover:bg-amber-500">
                                Upload Bukti
                            </button>
                        </div>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endsection