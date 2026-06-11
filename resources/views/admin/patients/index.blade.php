@extends('layouts.admin')

@section('title', 'Kelola Pasien')

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

    {{-- STATISTIK --}}
    <div class="mb-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
            <p class="text-xs text-gray-500">Total Pasien</p>
            <h3 class="text-3xl font-bold text-emerald-700 mt-2">
                {{ $totalPatients }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-400">
            <p class="text-xs text-gray-500">Daftar Hari Ini</p>
            <h3 class="text-3xl font-bold text-amber-500 mt-2">
                {{ $todayRegistrations }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500">Menunggu</p>
            <h3 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $pendingAppointments }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
            <p class="text-xs text-gray-500">Selesai</p>
            <h3 class="text-3xl font-bold text-green-600 mt-2">
                {{ $completedAppointments }}
            </h3>
        </div>
    </div>

    {{-- WRAPPER DATA PASIEN --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-emerald-700">
                    Data Pasien
                </h3>
                <p class="text-sm text-gray-500">
                    Kelola data pasien online dan pasien offline Kurnia Care.
                </p>
            </div>

            <a href="{{ route('admin.patients.create') }}"
                class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 text-center">
                + Tambah Pasien
            </a>
        </div>

        {{-- SEARCH --}}
        <form method="GET" action="{{ route('admin.patients.index') }}" class="mb-6 flex flex-col md:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Cari nama anak, orang tua, nomor HP, atau wilayah..."
                class="w-full h-12 rounded-xl border border-gray-300 px-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

            <button type="submit" class="px-5 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700">
                Cari
            </button>

            <a href="{{ route('admin.patients.index') }}"
                class="px-5 py-3 bg-gray-200 text-gray-700 rounded-xl text-center hover:bg-gray-300">
                Reset
            </a>
        </form>

        @if ($patients->count() > 0)

            {{-- ========================= --}}
            {{-- MODE HP / MOBILE: CARD --}}
            {{-- ========================= --}}
            <div class="md:hidden space-y-4">
                @foreach ($patients as $patient)
                    @php
                        $latestAppointment = $patient->appointments->sortByDesc('created_at')->first();
                        $paymentStatus = $latestAppointment?->payment?->status;
                    @endphp

                    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-4 shadow-sm">

                        {{-- NAMA DAN STATUS --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-gray-900 text-base">
                                    {{ $patient->child_name }}
                                </h4>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $patient->child_age }} tahun • {{ $patient->child_weight }} berat badan
                                </p>
                            </div>

                            @if ($latestAppointment)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if ($latestAppointment->status === 'selesai')
                                                    bg-green-100 text-green-700
                                                @elseif ($latestAppointment->status === 'diproses')
                                                    bg-blue-100 text-blue-700
                                                @elseif ($latestAppointment->status === 'batal')
                                                    bg-red-100 text-red-700
                                                @else
                                                    bg-amber-100 text-amber-700
                                                @endif">
                                    {{ ucfirst($latestAppointment->status) }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    Belum Ada
                                </span>
                            @endif
                        </div>

                        {{-- INFO MINI --}}
                        <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                            <div class="bg-white rounded-xl p-3 border border-emerald-50">
                                <p class="text-gray-500">Ayah</p>
                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $patient->father_name }}
                                </p>
                            </div>

                            <div class="bg-white rounded-xl p-3 border border-emerald-50">
                                <p class="text-gray-500">Ibu</p>
                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $patient->mother_name }}
                                </p>
                            </div>

                            <div class="bg-white rounded-xl p-3 border border-emerald-50">
                                <p class="text-gray-500">No HP</p>
                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $patient->phone }}
                                </p>
                            </div>

                            <div class="bg-white rounded-xl p-3 border border-emerald-50">
                                <p class="text-gray-500">Tipe</p>
                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ ucfirst($patient->registration_type ?? 'online') }}
                                </p>
                            </div>

                            <div class="bg-white rounded-xl p-3 border border-emerald-50 col-span-2">
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-semibold text-gray-800 mt-1 leading-relaxed">
                                    {{ $patient->village_name ?? '-' }},
                                    {{ $patient->district_name ?? '-' }},
                                    {{ $patient->city_name ?? '-' }},
                                    {{ $patient->province_name ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- DETAIL APPOINTMENT --}}
                        <div class="mt-4 bg-white rounded-xl p-3 border border-emerald-50">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Pendaftaran</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-1">
                                        {{ $patient->appointments->count() }} riwayat
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Pembayaran</p>
                                    <p class="text-sm font-semibold mt-1
                                                @if ($paymentStatus === 'diverifikasi')
                                                    text-green-600
                                                @elseif ($paymentStatus === 'ditolak')
                                                    text-red-600
                                                @elseif ($paymentStatus === 'pending')
                                                    text-amber-600
                                                @else
                                                    text-gray-500
                                                @endif">
                                        {{ $paymentStatus ? ucfirst($paymentStatus) : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-2 bg-emerald-500 rounded-full"
                                    style="width: {{ min($patient->appointments->count() * 25, 100) }}%">
                                </div>
                            </div>
                        </div>

                        {{-- AKSI --}}
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <a href="{{ route('admin.patients.show', $patient) }}"
                                class="py-2 text-center bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                                Detail
                            </a>

                            <a href="{{ route('admin.patients.edit', $patient) }}"
                                class="py-2 text-center bg-amber-400 text-gray-900 rounded-xl text-sm font-medium hover:bg-amber-500">
                                Edit
                            </a>

                            <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full py-2 text-center bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========================= --}}
            {{-- MODE DESKTOP: TABEL --}}
            {{-- ========================= --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Pasien</th>
                            <th class="px-4 py-3 text-left">Orang Tua</th>
                            <th class="px-4 py-3 text-left">Alamat</th>
                            <th class="px-4 py-3 text-left">Status Terakhir</th>
                            <th class="px-4 py-3 text-left">Pembayaran</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($patients as $patient)
                            @php
                                $latestAppointment = $patient->appointments->sortByDesc('created_at')->first();
                                $paymentStatus = $latestAppointment?->payment?->status;
                            @endphp

                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">
                                        {{ $patient->child_name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $patient->child_age }} tahun • {{ $patient->child_weight }} berat badan
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Tipe:
                                        <span class="font-medium">
                                            {{ ucfirst($patient->registration_type ?? 'online') }}
                                        </span>
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <p>Ayah: {{ $patient->father_name }}</p>
                                    <p>Ibu: {{ $patient->mother_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $patient->phone }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <p>{{ $patient->village_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $patient->district_name ?? '-' }},
                                        {{ $patient->city_name ?? '-' }},
                                        {{ $patient->province_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($latestAppointment)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if ($latestAppointment->status === 'selesai')
                                                            bg-green-100 text-green-700
                                                        @elseif ($latestAppointment->status === 'diproses')
                                                            bg-blue-100 text-blue-700
                                                        @elseif ($latestAppointment->status === 'batal')
                                                            bg-red-100 text-red-700
                                                        @else
                                                            bg-amber-100 text-amber-700
                                                        @endif">
                                            {{ ucfirst($latestAppointment->status) }}
                                        </span>

                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ $latestAppointment->service->name ?? '-' }}
                                        </p>
                                    @else
                                        <span class="text-gray-500">Belum ada</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($paymentStatus)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if ($paymentStatus === 'diverifikasi')
                                                            bg-green-100 text-green-700
                                                        @elseif ($paymentStatus === 'ditolak')
                                                            bg-red-100 text-red-700
                                                        @else
                                                            bg-amber-100 text-amber-700
                                                        @endif">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.patients.show', $patient) }}"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.patients.edit', $patient) }}"
                                            class="px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-6">
                {{ $patients->links() }}
            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-12 bg-gray-50 rounded-2xl">
                <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                    <span class="text-2xl">👤</span>
                </div>

                <p class="text-gray-600 font-semibold">
                    Belum ada data pasien.
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan pasien offline atau tunggu pasien melakukan pendaftaran online.
                </p>

                <a href="{{ route('admin.patients.create') }}"
                    class="inline-block mt-5 px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700">
                    Tambah Pasien
                </a>
            </div>
        @endif
    </div>

@endsection