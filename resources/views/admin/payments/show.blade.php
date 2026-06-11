@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

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

            <div
                class="bg-white rounded-xl shadow-sm p-5 border-l-4 
                    {{ $payment->status === 'ditolak' ? 'border-red-500' : ($payment->status === 'diverifikasi' ? 'border-emerald-500' : 'border-amber-400') }}">
                <p class="text-sm text-gray-500">Status Pembayaran</p>

                @if ($payment->status === 'pending')
                    <p class="mt-2 font-bold text-amber-600">Pending</p>
                @elseif ($payment->status === 'diverifikasi')
                    <p class="mt-2 font-bold text-emerald-700">Diverifikasi</p>
                @else
                    <p class="mt-2 font-bold text-red-600">Ditolak</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-400">
                <p class="text-sm text-gray-500">Nominal</p>
                <p class="mt-2 font-bold text-emerald-700">
                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                </p>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm p-5 border-l-4 
                    {{ $payment->appointment && $payment->appointment->status === 'batal' ? 'border-red-500' : 'border-emerald-500' }}">
                <p class="text-sm text-gray-500">Status Pendaftaran</p>

                @if ($payment->appointment)
                    @if ($payment->appointment->status === 'batal')
                        <p class="mt-2 font-bold text-red-600">Batal</p>
                    @elseif ($payment->appointment->status === 'diproses')
                        <p class="mt-2 font-bold text-blue-600">Diproses</p>
                    @elseif ($payment->appointment->status === 'selesai')
                        <p class="mt-2 font-bold text-emerald-700">Selesai</p>
                    @else
                        <p class="mt-2 font-bold text-amber-600">
                            {{ ucfirst($payment->appointment->status) }}
                        </p>
                    @endif
                @else
                    <p class="mt-2 font-bold text-gray-600">-</p>
                @endif
            </div>

        </div>

        {{-- DATA PASIEN DAN APPOINTMENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                    Data Pasien
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nama Anak</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->child_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Umur</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->child_age ?? '-' }} tahun
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Berat Badan</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->child_weight ?? '-' }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Nama Ayah</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->father_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Nama Ibu</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->mother_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">No HP / WA</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->phone ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Alamat</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->patient->address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                    Data Khitan
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Layanan</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->service->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Dokter</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->doctor->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Tanggal Khitan</p>
                        <p class="font-semibold">
                            @if ($payment->appointment && $payment->appointment->appointment_date)
                                {{ $payment->appointment->appointment_date->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Hari</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->appointment_day ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jam</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->appointment_time ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jenis Obat</p>
                        <p class="font-semibold">
                            {{ $payment->appointment && $payment->appointment->medicine_type ? ucfirst($payment->appointment->medicine_type) : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Paket Khitan</p>
                        <p class="font-semibold">
                            {{ $payment->appointment->circumcision_package ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- BUKTI PEMBAYARAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-emerald-700 mb-4">
                Bukti Pembayaran
            </h3>

            @if ($payment->proof_image)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div>
                        <p class="text-sm text-gray-500 mb-3">
                            Klik gambar untuk membuka ukuran penuh.
                        </p>

                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti Pembayaran"
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
                            <p class="text-sm text-gray-500">Diverifikasi Oleh</p>
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
                        Pasien belum mengupload bukti pembayaran.
                    </p>
                </div>
            @endif
        </div>

        {{-- ALASAN PENOLAKAN --}}
        @if ($payment->status === 'ditolak' && $payment->rejection_reason)
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

            @if ($payment->status === 'diverifikasi')
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4">
                    <p class="text-emerald-700 font-medium">
                        Pembayaran ini sudah diverifikasi.
                    </p>

                    @if ($payment->verified_at)
                        <p class="text-sm text-gray-500 mt-1">
                            Diverifikasi pada {{ $payment->verified_at->format('d-m-Y H:i') }}
                        </p>
                    @endif
                </div>

            @elseif ($payment->status === 'ditolak')
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-red-700 font-medium">
                        Pembayaran ini sudah ditolak dan tidak bisa diverifikasi ulang.
                    </p>

                    @if ($payment->appointment && $payment->appointment->status === 'batal')
                        <p class="text-sm text-gray-500 mt-1">
                            Transaksi sudah dibatalkan.
                        </p>
                    @endif
                </div>

            @elseif ($payment->appointment && $payment->appointment->status === 'batal')
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-red-700 font-medium">
                        Transaksi sudah batal dan tidak bisa diverifikasi.
                    </p>
                </div>

            @elseif (!$payment->proof_image)
                <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-amber-700 font-medium">
                        Bukti pembayaran belum diupload oleh pasien.
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Admin baru bisa memverifikasi setelah pasien mengupload bukti pembayaran.
                    </p>
                </div>

            @else

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- VERIFIKASI --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                        <h4 class="font-semibold text-emerald-700 mb-2">
                            Verifikasi Pembayaran
                        </h4>

                        <p class="text-sm text-gray-600 mb-4">
                            Klik tombol verifikasi jika bukti transfer sudah sesuai.
                        </p>

                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                            @csrf

                            <button type="submit" onclick="return confirm('Yakin ingin memverifikasi pembayaran ini?')"
                                class="w-full px-5 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Verifikasi Pembayaran
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

                            <textarea name="rejection_reason" rows="3"
                                placeholder="Contoh: Nominal tidak sesuai / bukti tidak jelas"
                                class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none">{{ old('rejection_reason') }}</textarea>

                            <button type="submit"
                                onclick="return confirm('Yakin ingin menolak pembayaran ini? Transaksi akan dibatalkan.')"
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