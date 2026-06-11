@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-800">
                Dashboard Admin
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan sistem Klinik Sunat Modern Kurnia Care.
            </p>
        </div>

        {{-- KARTU UTAMA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Pasien</p>
                <h4 class="mt-3 text-3xl font-bold text-emerald-700">{{ $totalPatients }}</h4>
                <p class="mt-2 text-xs text-gray-400">Jumlah data pasien terdaftar</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Pendaftaran</p>
                <h4 class="mt-3 text-3xl font-bold text-blue-700">{{ $totalAppointments }}</h4>
                <p class="mt-2 text-xs text-gray-400">Semua data appointment</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Pembayaran Diverifikasi</p>
                <h4 class="mt-3 text-3xl font-bold text-emerald-600">{{ $verifiedPayments }}</h4>
                <p class="mt-2 text-xs text-gray-400">Pembayaran berhasil diverifikasi</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Transaksi Batal</p>
                <h4 class="mt-3 text-3xl font-bold text-red-600">{{ $cancelledAppointments }}</h4>
                <p class="mt-2 text-xs text-gray-400">Appointment yang dibatalkan</p>
            </div>
        </div>

        {{-- GRAFIK --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Grafik Status Pendaftaran --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Status Pendaftaran</h3>
                        <p class="text-sm text-gray-500">Distribusi appointment saat ini</p>
                    </div>
                </div>

                <div class="h-80">
                    <canvas id="appointmentChart"></canvas>
                </div>
            </div>

            {{-- Grafik Status Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Status Pembayaran</h3>
                        <p class="text-sm text-gray-500">Ringkasan pembayaran pasien</p>
                    </div>
                </div>

                <div class="h-80">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RINGKASAN STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
                <p class="text-sm text-amber-700">Menunggu</p>
                <h4 class="mt-2 text-2xl font-bold text-amber-600">{{ $waitingAppointments }}</h4>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                <p class="text-sm text-blue-700">Diproses Dokter</p>
                <h4 class="mt-2 text-2xl font-bold text-blue-700">{{ $processedAppointments }}</h4>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
                <p class="text-sm text-red-700">Pembayaran Pending</p>
                <h4 class="mt-2 text-2xl font-bold text-red-600">{{ $pendingPayments }}</h4>
            </div>

            <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5">
                <p class="text-sm text-rose-700">Pembayaran Ditolak</p>
                <h4 class="mt-2 text-2xl font-bold text-rose-600">{{ $rejectedPayments }}</h4>
            </div>
        </div>

        {{-- DATA TERBARU --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- PENDAFTARAN TERBARU --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pendaftaran Terbaru</h3>
                        <p class="text-sm text-gray-500">5 data appointment terakhir</p>
                    </div>
                </div>

                @if ($latestAppointments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($latestAppointments as $appointment)
                            <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $appointment->patient->child_name ?? '-' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $appointment->service->name ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">
                                            {{ $appointment->created_at->format('d-m-Y H:i') }}
                                        </p>
                                    </div>

                                    <div>
                                        @if ($appointment->status === 'menunggu')
                                            <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                                                Menunggu
                                            </span>
                                        @elseif ($appointment->status === 'diproses')
                                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                Diproses
                                            </span>
                                        @elseif ($appointment->status === 'selesai')
                                            <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                                Selesai
                                            </span>
                                        @elseif ($appointment->status === 'batal')
                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                Batal
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada data pendaftaran.</p>
                @endif
            </div>

            {{-- PEMBAYARAN TERBARU --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pembayaran Terbaru</h3>
                        <p class="text-sm text-gray-500">5 data pembayaran terakhir</p>
                    </div>

                    <a href="{{ route('admin.payments.index') }}"
                        class="text-sm font-semibold text-emerald-700 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                @if ($latestPayments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($latestPayments as $payment)
                            <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $payment->appointment->patient->child_name ?? '-' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        @if ($payment->status === 'pending')
                                            <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                                                Pending
                                            </span>
                                        @elseif ($payment->status === 'diverifikasi')
                                            <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                                Diverifikasi
                                            </span>
                                        @elseif ($payment->status === 'ditolak')
                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        @endif

                                        <div class="mt-2">
                                            <a href="{{ route('admin.payments.show', $payment) }}"
                                                class="text-sm text-emerald-700 font-semibold hover:underline">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada data pembayaran.</p>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const appointmentCtx = document.getElementById('appointmentChart');
        const paymentCtx = document.getElementById('paymentChart');

        new Chart(appointmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Diproses', 'Selesai', 'Batal'],
                datasets: [{
                    data: [
                            {{ $waitingAppointments }},
                            {{ $processedAppointments }},
                            {{ $completedAppointments }},
                        {{ $cancelledAppointments }}
                    ],
                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '68%'
            }
        });

        new Chart(paymentCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Perlu Verifikasi', 'Diverifikasi', 'Ditolak'],
                datasets: [{
                    label: 'Jumlah',
                    data: [
                            {{ $pendingPayments }},
                            {{ $needVerificationPayments }},
                            {{ $verifiedPayments }},
                        {{ $rejectedPayments }}
                    ],
                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endpush