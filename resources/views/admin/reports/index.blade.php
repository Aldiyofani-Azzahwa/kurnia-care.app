@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-emerald-700">
            Laporan Klinik
        </h3>

        <p class="text-sm text-gray-500">
            Laporan appointment pasien, status tindakan, pembayaran, dan pendapatan klinik.
        </p>
    </div>

    <a href="{{ route('admin.reports.print', request()->query()) }}"
       target="_blank"
       class="px-5 py-3 bg-amber-400 text-gray-900 font-semibold rounded-xl hover:bg-amber-500 text-center">
        Cetak / Export PDF
    </a>
</div>

{{-- FILTER --}}
<div class="bg-white rounded-2xl shadow-sm p-4 md:p-6 mb-6">
    <form method="GET"
          action="{{ route('admin.reports.index') }}"
          class="grid grid-cols-1 md:grid-cols-6 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1">
                Jenis Laporan
            </label>

            <select name="type"
                    id="report_type"
                    class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                <option value="daily" @selected($type === 'daily')>
                    Harian
                </option>
                <option value="monthly" @selected($type === 'monthly')>
                    Bulanan
                </option>
                <option value="custom" @selected($type === 'custom')>
                    Custom
                </option>
            </select>
        </div>

        <div id="daily_filter">
            <label class="block text-sm font-medium mb-1">
                Tanggal
            </label>

            <input type="date"
                   name="date"
                   value="{{ $date }}"
                   class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div id="monthly_filter">
            <label class="block text-sm font-medium mb-1">
                Bulan
            </label>

            <input type="month"
                   name="month"
                   value="{{ $month }}"
                   class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div id="custom_start_filter">
            <label class="block text-sm font-medium mb-1">
                Dari Tanggal
            </label>

            <input type="date"
                   name="start_date"
                   value="{{ request('start_date', $startDate) }}"
                   class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div id="custom_end_filter">
            <label class="block text-sm font-medium mb-1">
                Sampai Tanggal
            </label>

            <input type="date"
                   name="end_date"
                   value="{{ request('end_date', $endDate) }}"
                   class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Status Tindakan
            </label>

            <select name="status"
                    class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                <option value="">Semua</option>
                <option value="menunggu" @selected($status === 'menunggu')>Menunggu</option>
                <option value="dikonfirmasi" @selected($status === 'dikonfirmasi')>Dikonfirmasi</option>
                <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Status Bayar
            </label>

            <select name="payment_status"
                    class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                <option value="">Semua</option>
                <option value="pending" @selected($paymentStatus === 'pending')>Pending</option>
                <option value="diterima" @selected($paymentStatus === 'diterima')>Diterima</option>
                <option value="ditolak" @selected($paymentStatus === 'ditolak')>Ditolak</option>
            </select>
        </div>

        <div class="md:col-span-6 flex flex-col md:flex-row gap-3 md:justify-end">
            <a href="{{ route('admin.reports.index') }}"
               class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl text-center hover:bg-gray-300">
                Reset
            </a>

            <button type="submit"
                    class="px-5 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700">
                Tampilkan Laporan
            </button>
        </div>
    </form>
</div>

{{-- PERIODE --}}
<div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-700">
    <p class="text-sm">
        Periode laporan:
        <strong>
            {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') }}
            -
            {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y') }}
        </strong>
    </p>
</div>

{{-- STATISTIK --}}
<div class="mb-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-xs text-gray-500">Total Appointment</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">
            {{ $totalAppointments }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-400">
        <p class="text-xs text-gray-500">Menunggu</p>
        <h3 class="text-3xl font-bold text-amber-500 mt-2">
            {{ $waitingCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">dikonfirmasi</p>
        <h3 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $processCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Selesai</p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">
            {{ $doneCount }}
        </h3>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-red-400">
        <p class="text-xs text-gray-500">Dibatalkan</p>
        <h3 class="text-3xl font-bold text-red-500 mt-2">
            {{ $cancelCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Pembayaran Diterima</p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">
            {{ $verifiedPaymentCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-400">
        <p class="text-xs text-gray-500">Pembayaran Pending</p>
        <h3 class="text-3xl font-bold text-amber-500 mt-2">
            {{ $pendingPaymentCount }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-xs text-gray-500">Pendapatan Terverifikasi</p>
        <h3 class="text-2xl font-bold text-emerald-700 mt-2">
            Rp{{ number_format($totalRevenue, 0, ',', '.') }}
        </h3>
    </div>
</div>

{{-- REKAP LAYANAN --}}
<div class="bg-white rounded-2xl shadow-sm p-4 md:p-6 mb-6">
    <h3 class="text-lg font-semibold text-emerald-700 mb-4">
        Rekap Berdasarkan Layanan
    </h3>

    @if ($serviceRecaps->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($serviceRecaps as $service)
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="font-semibold text-gray-900">
                        {{ $service['service_name'] }}
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        Total appointment: {{ $service['total'] }}
                    </p>

                    <p class="text-sm text-emerald-700 font-semibold mt-1">
                        Pendapatan: Rp{{ number_format($service['revenue'], 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 bg-gray-50 rounded-xl text-gray-500">
            Belum ada data layanan pada periode ini.
        </div>
    @endif
</div>

{{-- TABEL DETAIL --}}
<div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">
    <h3 class="text-lg font-semibold text-emerald-700 mb-4">
        Detail Appointment
    </h3>

    @if ($appointments->count() > 0)

        {{-- MOBILE CARD --}}
        <div class="md:hidden space-y-4">
            @foreach ($appointments as $appointment)
                <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-4 shadow-sm">
                    <div class="flex justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-gray-900">
                                {{ $appointment->patient->child_name ?? '-' }}
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $appointment->appointment_date?->format('d/m/Y') }}
                                •
                                {{ substr($appointment->appointment_time, 0, 5) }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if ($appointment->status === 'selesai')
                                bg-green-100 text-green-700
                            @elseif ($appointment->status === 'dikonfirmasi')
                                bg-blue-100 text-blue-700
                            @elseif ($appointment->status === 'dibatalkan')
                                bg-red-100 text-red-700
                            @else
                                bg-amber-100 text-amber-700
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white rounded-xl p-3">
                            <p class="text-gray-500">Layanan</p>
                            <p class="font-semibold mt-1">
                                {{ $appointment->service->name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3">
                            <p class="text-gray-500">Dokter</p>
                            <p class="font-semibold mt-1">
                                {{ $appointment->doctor->name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3">
                            <p class="text-gray-500">Pembayaran</p>
                            <p class="font-semibold mt-1">
                                {{ ucfirst($appointment->payment->status ?? '-') }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl p-3">
                            <p class="text-gray-500">Nominal</p>
                            <p class="font-semibold mt-1">
                                Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-emerald-700 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Pasien</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Dokter</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Pembayaran</th>
                        <th class="px-4 py-3 text-left">Nominal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                                <span class="text-xs text-gray-500">
                                    {{ substr($appointment->appointment_time, 0, 5) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <p class="font-semibold">
                                    {{ $appointment->patient->child_name ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $appointment->patient->phone ?? '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-3">
                                {{ $appointment->service->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $appointment->doctor->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst($appointment->status) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst($appointment->payment->status ?? '-') }}
                            </td>

                            <td class="px-4 py-3">
                                Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

    @else
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <p class="text-gray-600 font-semibold">
                Tidak ada data laporan pada periode ini.
            </p>

            <p class="text-sm text-gray-500 mt-1">
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