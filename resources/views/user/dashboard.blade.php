@extends('layouts.user')

@section('title', 'Dashboard Pasien')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500">Pendaftaran Aktif</p>
            <h3 class="text-3xl font-bold text-emerald-700 mt-2"> {{ $activeAppointments }}</h3>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-400">
            <p class="text-sm text-gray-500">Pembayaran Pending</p>
            <h3 class="text-3xl font-bold text-emerald-700 mt-2"> {{ $pendingPayments }}</h3>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500">Riwayat Pendaftaran</p>
            <h3 class="text-3xl font-bold text-emerald-700 mt-2"> {{ $totalAppointments }}</h3>
        </div>

    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Selamat Datang</h3>
        <p class="text-gray-600 mb-4">
            Dari dashboard ini Anda dapat melakukan pendaftaran sunat online,
            melihat status pendaftaran, upload bukti pembayaran, dan melihat riwayat.
        </p>

        <a href="{{ route('user.appointments.create') }}"
            class="inline-block px-5 py-3 bg-amber-400 text-gray-900 font-semibold rounded-lg hover:bg-amber-500 transition">
            Daftar Sunat Sekarang
        </a>
    </div>

@endsection