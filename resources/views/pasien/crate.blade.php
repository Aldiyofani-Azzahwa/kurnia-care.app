@extends('layout')

@section('content')

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Tambah Pasien</h2>

    <form action="{{ route('pasien.store') }}" method="POST">
        @csrf

        <input type="text" name="nama" placeholder="Nama"
            class="border p-2 w-full mb-2">

        <input type="number" name="umur" placeholder="Umur"
            class="border p-2 w-full mb-2">

        <textarea name="alamat" placeholder="Alamat"
            class="border p-2 w-full mb-2"></textarea>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>

@endsection