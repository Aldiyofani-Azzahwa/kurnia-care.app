@extends('layouts.admin')

@section('title', 'Tambah Dokter')

@section('content')

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

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-emerald-700">
            Tambah Dokter
        </h3>

        <p class="text-sm text-gray-500">
            Tambahkan data dokter sekaligus akun login dokter.
        </p>
    </div>

    <a href="{{ route('admin.doctors.index') }}"
       class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.doctors.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        @include('admin.doctors.form')

        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('admin.doctors.index') }}"
               class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Simpan Dokter
            </button>
        </div>
    </form>
</div>

@endsection