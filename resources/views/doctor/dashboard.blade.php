@extends('layouts.doctor')

@section('title', 'Dashboard Dokter')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
        <p class="text-sm text-gray-500">Jadwal Hari Ini</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">{{ $todayAppointments }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-400">
        <p class="text-sm text-gray-500">Menunggu</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">{{ $processedAppointments }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
        <p class="text-sm text-gray-500">Selesai</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">{{ $completedAppointments }}</h3>
    </div>

</div>

<div class="mt-8 bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">Informasi Dokter</h3>
    <p class="text-gray-600">
        Dari dashboard ini dokter dapat melihat jadwal pasien, detail pasien,
        menambahkan catatan tindakan, dan mengubah status tindakan.
    </p>
</div>

@endsection