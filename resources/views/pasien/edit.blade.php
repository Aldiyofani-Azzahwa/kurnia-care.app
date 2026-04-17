@extends('layout')

@section('content')

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Pasien</h2>

    <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="nama" value="{{ $pasien->nama }}"
            class="border p-2 w-full mb-2">

        <input type="number" name="umur" value="{{ $pasien->umur }}"
            class="border p-2 w-full mb-2">

        <textarea name="alamat"
            class="border p-2 w-full mb-2">{{ $pasien->alamat }}</textarea>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>
</div>

@endsection