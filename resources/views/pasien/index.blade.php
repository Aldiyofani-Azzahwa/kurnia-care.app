@extends('layout')

@section('content')

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Data Pasien</h2>

    <a href="{{ route('pasien.create') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded">
       + Tambah Pasien
    </a>

    <table class="w-full mt-4 border">
        <tr class="bg-gray-200">
            <th class="p-2">No</th>
            <th>Nama</th>
            <th>Umur</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>

        @foreach ($pasien as $p)
        <tr class="text-center border-t">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->umur }}</td>
            <td>{{ $p->alamat }}</td>
            <td>
                <a href="{{ route('pasien.edit', $p->id) }}" 
                   class="bg-yellow-400 px-2 py-1 rounded">Edit</a>

                <form action="{{ route('pasien.destroy', $p->id) }}" 
                      method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 text-white px-2 py-1 rounded">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>
</div>

@endsection