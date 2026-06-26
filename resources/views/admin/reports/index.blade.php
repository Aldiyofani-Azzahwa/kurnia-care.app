@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')

@php
    $formatRupiah = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
    $periodText = \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') . ' - ' . \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y');
@endphp

<div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-500 p-6 text-white shadow-sm">
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white p-2 shadow-sm">
                <img
                    src="{{ asset('images/logo-kurnia-care.png') }}"
                    alt="Logo Kurnia Care"
                    class="h-full w-full object-contain"
                >
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-100">
                    Kurnia Care
                </p>
                <h3 class="mt-1 text-2xl font-bold">
                    Laporan Klinik
                </h3>
                <p class="mt-1 text-sm text-emerald-50">
                    Rekap appointment, tindakan, pembayaran DP, dan pendapatan lunas layanan.
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-3 md:items-end">
            <div class="rounded-2xl bg-white/15 px-4 py-3 text-sm backdrop-blur">
                <p class="text-emerald-50">Periode</p>
                <p class="font-bold">{{ $periodText }}</p>
            </div>

            <a href="{{ route('admin.reports.print', request()->query()) }}"
               target="_blank"
               class="rounded-xl bg-amber-400 px-5 py-3 text-center text-sm font-bold text-gray-900 transition hover:bg-amber-500">
                Cetak / Simpan PDF
            </a>
        </div>
    </div>
</div>

<div class="mb-6 rounded-3xl bg-white p-4 shadow-sm md:p-6">
    <form method="GET"
          action="{{ route('admin.reports.index') }}"
          class="grid grid-cols-1 gap-4 md:grid-cols-6">

        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Jenis Laporan
            </label>

            <select name="type"
                    id="report_type"
                    class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                <option value="daily" @selected($type === 'daily')>Harian</option>
                <option value="monthly" @selected($type === 'monthly')>Bulanan</option>
                <option value="custom" @selected($type === 'custom')>Custom</option>
            </select>
        </div>

        <div id="daily_filter">
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Tanggal
            </label>

            <input type="date"
                   name="date"
                   value="{{ $date }}"
                   class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        </div>

        <div id="monthly_filter">
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Bulan
            </label>

            <input type="month"
                   name="month"
                   value="{{ $month }}"
                   class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        </div>

        <div id="custom_start_filter">
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Dari Tanggal
            </label>

            <input type="date"
                   name="start_date"
                   value="{{ request('start_date', $startDate) }}"
                   class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        </div>

        <div id="custom_end_filter">
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Sampai Tanggal
            </label>

            <input type="date"
                   name="end_date"
                   value="{{ request('end_date', $endDate) }}"
                   class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Status Tindakan
            </label>

            <select name="status"
                    class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                <option value="">Semua</option>
                <option value="menunggu" @selected($status === 'menunggu')>Menunggu</option>
                <option value="dikonfirmasi" @selected($status === 'dikonfirmasi')>Dikonfirmasi</option>
                <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700">
                Status Bayar
            </label>

            <select name="payment_status"
                    class="h-12 w-full rounded-xl border border-gray-300 px-4 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                <option value="">Semua</option>
                <option value="pending" @selected($paymentStatus === 'pending')>Pending</option>
                <option value="diterima" @selected($paymentStatus === 'diterima')>Diterima</option>
                <option value="ditolak" @selected($paymentStatus === 'ditolak')>Ditolak</option>
            </select>
        </div>

        <div class="flex flex-col gap-3 md:col-span-6 md:flex-row md:justify-end">
            <a href="{{ route('admin.reports.index') }}"
               class="rounded-xl bg-gray-200 px-5 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-300">
                Reset
            </a>

            <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                Tampilkan Laporan
            </button>
        </div>
    </form>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <p class="text-sm font-semibold text-gray-500">Pendapatan Lunas</p>
        <h3 class="mt-2 text-3xl font-bold text-emerald-700">
            {{ $formatRupiah($totalRevenue) }}
        </h3>
        <p class="mt-2 text-xs text-gray-500">
            Dihitung dari harga layanan pada pembayaran diterima.
        </p>
    </div>

    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <p class="text-sm font-semibold text-gray-500">DP Masuk</p>
        <h3 class="mt-2 text-3xl font-bold text-amber-500">
            {{ $formatRupiah($totalDpRevenue) }}
        </h3>
        <p class="mt-2 text-xs text-gray-500">
            Nominal dari tabel pembayaran.
        </p>
    </div>

    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <p class="text-sm font-semibold text-gray-500">Sisa Pelunasan</p>
        <h3 class="mt-2 text-3xl font-bold text-blue-600">
            {{ $formatRupiah($totalRemainingRevenue) }}
        </h3>
        <p class="mt-2 text-xs text-gray-500">
            Harga layanan dikurangi DP.
        </p>
    </div>

    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <p class="text-sm font-semibold text-gray-500">Pembayaran Diterima</p>
        <h3 class="mt-2 text-3xl font-bold text-green-600">
            {{ $verifiedPaymentCount }}
        </h3>
        <p class="mt-2 text-xs text-gray-500">
            Jumlah appointment dengan pembayaran diterima.
        </p>
    </div>
</div>

<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="rounded-2xl border-l-4 border-emerald-500 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Total Appointment</p>
        <h3 class="mt-2 text-3xl font-bold text-emerald-700">{{ $totalAppointments }}</h3>
    </div>

    <div class="rounded-2xl border-l-4 border-amber-400 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Menunggu</p>
        <h3 class="mt-2 text-3xl font-bold text-amber-500">{{ $waitingCount }}</h3>
    </div>

    <div class="rounded-2xl border-l-4 border-blue-500 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Dikonfirmasi</p>
        <h3 class="mt-2 text-3xl font-bold text-blue-600">{{ $processCount }}</h3>
    </div>

    <div class="rounded-2xl border-l-4 border-green-500 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Selesai</p>
        <h3 class="mt-2 text-3xl font-bold text-green-600">{{ $doneCount }}</h3>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="rounded-2xl border-l-4 border-red-400 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Dibatalkan</p>
        <h3 class="mt-2 text-3xl font-bold text-red-500">{{ $cancelCount }}</h3>
    </div>

    <div class="rounded-2xl border-l-4 border-amber-400 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Pembayaran Pending</p>
        <h3 class="mt-2 text-3xl font-bold text-amber-500">{{ $pendingPaymentCount }}</h3>
    </div>

    <div class="rounded-2xl border-l-4 border-red-400 bg-white p-5 shadow-sm">
        <p class="text-xs text-gray-500">Pembayaran Ditolak</p>
        <h3 class="mt-2 text-3xl font-bold text-red-500">{{ $rejectedPaymentCount }}</h3>
    </div>
</div>

<div class="mb-6 rounded-3xl bg-white p-4 shadow-sm md:p-6">
    <div class="mb-4 flex flex-col gap-1 md:flex-row md:items-end md:justify-between">
        <div>
            <h3 class="text-lg font-bold text-emerald-700">
                Rekap Berdasarkan Layanan
            </h3>
            <p class="text-sm text-gray-500">
                Pendapatan memakai harga layanan, bukan nominal DP.
            </p>
        </div>
    </div>

    @if ($serviceRecaps->count() > 0)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($serviceRecaps as $service)
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="font-bold text-gray-900">
                        {{ $service['service_name'] }}
                    </p>

                    <div class="mt-3 space-y-1 text-sm">
                        <p class="text-gray-600">Total appointment: <strong>{{ $service['total'] }}</strong></p>
                        <p class="text-gray-600">Pembayaran diterima: <strong>{{ $service['paid_count'] }}</strong></p>
                        <p class="font-semibold text-amber-600">DP masuk: {{ $formatRupiah($service['dp_revenue']) }}</p>
                        <p class="font-bold text-emerald-700">Pendapatan lunas: {{ $formatRupiah($service['revenue']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-xl bg-gray-50 py-8 text-center text-gray-500">
            Belum ada data layanan pada periode ini.
        </div>
    @endif
</div>

<div class="rounded-3xl bg-white p-4 shadow-sm md:p-6">
    <div class="mb-4 flex flex-col gap-1 md:flex-row md:items-end md:justify-between">
        <div>
            <h3 class="text-lg font-bold text-emerald-700">
                Detail Appointment
            </h3>
            <p class="text-sm text-gray-500">
                Kolom pendapatan lunas dihitung dari harga layanan jika pembayaran diterima.
            </p>
        </div>
    </div>

    @if ($appointments->count() > 0)
        <div class="space-y-4 md:hidden">
            @foreach ($appointments as $appointment)
                @php
                    $dpAmount = (float) (optional($appointment->payment)->amount ?? 0);
                    $servicePrice = (float) (optional($appointment->service)->price ?? 0);
                    $fullAmount = optional($appointment->payment)->status === 'diterima' ? ($servicePrice > 0 ? $servicePrice : $dpAmount) : 0;
                    $remainingAmount = max($fullAmount - $dpAmount, 0);
                @endphp

                <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-4 shadow-sm">
                    <div class="flex justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-gray-900">
                                {{ $appointment->patient->child_name ?? '-' }}
                            </h4>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $appointment->appointment_date?->format('d/m/Y') }}
                                •
                                {{ substr($appointment->appointment_time, 0, 5) }}
                            </p>
                        </div>

                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                            @if ($appointment->status === 'selesai') bg-green-100 text-green-700
                            @elseif ($appointment->status === 'dikonfirmasi') bg-blue-100 text-blue-700
                            @elseif ($appointment->status === 'dibatalkan') bg-red-100 text-red-700
                            @else bg-amber-100 text-amber-700
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl bg-white p-3">
                            <p class="text-gray-500">Layanan</p>
                            <p class="mt-1 font-semibold">{{ $appointment->service->name ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl bg-white p-3">
                            <p class="text-gray-500">Pembayaran</p>
                            <p class="mt-1 font-semibold">{{ ucfirst($appointment->payment->status ?? '-') }}</p>
                        </div>

                        <div class="rounded-xl bg-white p-3">
                            <p class="text-gray-500">DP</p>
                            <p class="mt-1 font-semibold">{{ $formatRupiah($dpAmount) }}</p>
                        </div>

                        <div class="rounded-xl bg-white p-3">
                            <p class="text-gray-500">Pendapatan Lunas</p>
                            <p class="mt-1 font-semibold text-emerald-700">{{ $formatRupiah($fullAmount) }}</p>
                        </div>

                        <div class="col-span-2 rounded-xl bg-white p-3">
                            <p class="text-gray-500">Sisa Pelunasan</p>
                            <p class="mt-1 font-semibold text-blue-600">{{ $formatRupiah($remainingAmount) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="hidden overflow-x-auto md:block">
            <table class="w-full overflow-hidden rounded-2xl border border-gray-200 text-sm">
                <thead class="bg-emerald-700 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Pasien</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Dokter</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Pembayaran</th>
                        <th class="px-4 py-3 text-left">DP</th>
                        <th class="px-4 py-3 text-left">Pendapatan Lunas</th>
                        <th class="px-4 py-3 text-left">Sisa</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($appointments as $appointment)
                        @php
                            $dpAmount = (float) (optional($appointment->payment)->amount ?? 0);
                            $servicePrice = (float) (optional($appointment->service)->price ?? 0);
                            $fullAmount = optional($appointment->payment)->status === 'diterima' ? ($servicePrice > 0 ? $servicePrice : $dpAmount) : 0;
                            $remainingAmount = max($fullAmount - $dpAmount, 0);
                        @endphp

                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                                <span class="text-xs text-gray-500">
                                    {{ substr($appointment->appointment_time, 0, 5) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ $appointment->patient->child_name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->patient->phone ?? '-' }}</p>
                            </td>

                            <td class="px-4 py-3">{{ $appointment->service->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $appointment->doctor->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($appointment->status) }}</td>
                            <td class="px-4 py-3">{{ ucfirst($appointment->payment->status ?? '-') }}</td>
                            <td class="px-4 py-3">{{ $formatRupiah($dpAmount) }}</td>
                            <td class="px-4 py-3 font-bold text-emerald-700">{{ $formatRupiah($fullAmount) }}</td>
                            <td class="px-4 py-3 text-blue-600">{{ $formatRupiah($remainingAmount) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @else
        <div class="rounded-2xl bg-gray-50 py-12 text-center">
            <p class="font-semibold text-gray-600">
                Tidak ada data laporan pada periode ini.
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Coba ubah filter tanggal atau status laporan.
            </p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('report_type');
        const dailyFilter = document.getElementById('daily_filter');
        const monthlyFilter = document.getElementById('monthly_filter');
        const customStartFilter = document.getElementById('custom_start_filter');
        const customEndFilter = document.getElementById('custom_end_filter');

        function toggleFilters() {
            const type = typeSelect.value;

            dailyFilter.style.display = type === 'daily' ? 'block' : 'none';
            monthlyFilter.style.display = type === 'monthly' ? 'block' : 'none';
            customStartFilter.style.display = type === 'custom' ? 'block' : 'none';
            customEndFilter.style.display = type === 'custom' ? 'block' : 'none';
        }

        typeSelect.addEventListener('change', toggleFilters);
        toggleFilters();
    });
</script>

@endsection