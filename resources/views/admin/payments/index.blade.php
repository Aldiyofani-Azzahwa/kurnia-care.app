@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

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

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-emerald-700">
                    Data Pembayaran
                </h3>

                <p class="text-sm text-gray-500">
                    Kelola dan verifikasi pembayaran pasien Kurnia Care.
                </p>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.payments.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama anak / orang tua / no HP"
                    class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none text-sm">

                <select name="status"
                    class="w-full h-12 rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="diverifikasi" @selected($status === 'diverifikasi')>Diverifikasi</option>
                    <option value="ditolak" @selected($status === 'ditolak')>Ditolak</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 md:flex-none px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Filter
                    </button>

                    <a href="{{ route('admin.payments.index') }}"
                        class="flex-1 md:flex-none px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        @if ($payments->count() > 0)

            {{-- MOBILE CARD VIEW --}}
            <div class="md:hidden space-y-4">

                @foreach ($payments as $payment)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4">

                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <h4 class="font-bold text-emerald-700 truncate">
                                    {{ $payment->appointment->patient->child_name ?? '-' }}
                                </h4>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $payment->appointment->patient->phone ?? '-' }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if ($payment->status === 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                                        Pending
                                    </span>
                                @elseif ($payment->status === 'diverifikasi')
                                    <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                        Diverifikasi
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between gap-4 border-t pt-3">
                                <span class="text-gray-500">Layanan</span>
                                <span class="font-semibold text-right">
                                    {{ $payment->appointment->service->name ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Tanggal Khitan</span>
                                <span class="font-semibold text-right">
                                    @if ($payment->appointment && $payment->appointment->appointment_date)
                                        {{ $payment->appointment->appointment_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Nominal</span>
                                <span class="font-bold text-emerald-700 text-right">
                                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Bukti</span>
                                <span class="font-semibold text-right">
                                    @if ($payment->proof_image)
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Ada
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            Belum
                                        </span>
                                    @endif
                                </span>
                            </div>

                            @if ($payment->appointment && $payment->appointment->status === 'batal')
                                <div class="rounded-lg bg-red-50 border border-red-200 p-3">
                                    <p class="text-sm font-semibold text-red-700">
                                        Transaksi Gagal
                                    </p>
                                </div>
                            @endif

                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.payments.show', $payment) }}"
                                class="block w-full text-center px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold">
                                Detail Pembayaran
                            </a>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Pasien</th>
                            <th class="px-4 py-3 text-left">Layanan</th>
                            <th class="px-4 py-3 text-left">Tanggal Khitan</th>
                            <th class="px-4 py-3 text-left">Nominal</th>
                            <th class="px-4 py-3 text-left">Bukti</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($payments as $payment)
                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-4 py-3">
                                    <p class="font-semibold">
                                        {{ $payment->appointment->patient->child_name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $payment->appointment->patient->phone ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    {{ $payment->appointment->service->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($payment->appointment && $payment->appointment->appointment_date)
                                        {{ $payment->appointment->appointment_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($payment->proof_image)
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Ada
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            Belum
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($payment->status === 'pending')
                                        <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                                            Pending
                                        </span>
                                    @elseif ($payment->status === 'diverifikasi')
                                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                            Diverifikasi
                                        </span>
                                    @else
                                        <div class="space-y-1">
                                            <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                                Ditolak
                                            </span>

                                            @if ($payment->appointment && $payment->appointment->status === 'batal')
                                                <div>
                                                    <span class="px-3 py-1 rounded-full text-xs bg-red-200 text-red-800 font-semibold">
                                                        Transaksi Gagal
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        Detail
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">
                    Belum ada data pembayaran.
                </p>

                <p class="text-sm text-gray-400">
                    Data pembayaran akan muncul setelah pasien melakukan pendaftaran.
                </p>
            </div>

        @endif

    </div>

@endsection