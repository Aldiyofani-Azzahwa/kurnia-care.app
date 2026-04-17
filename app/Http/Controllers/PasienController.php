<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    // tampilkan semua data
    public function index()
    {
        $pasien = Pasien::all();
        return view('pasien.index', compact('pasien'));
    }

    // form tambah
    public function create()
    {
        return view('pasien.create');
    }

    // simpan data
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'umur' => 'required|numeric',
            'alamat' => 'required',
        ]);

        Pasien::create($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan');
    }

    // tampil detail
    public function show($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.show', compact('pasien'));
    }

    // form edit
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    // update data
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'umur' => 'required|numeric',
            'alamat' => 'required',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diupdate');
    }

    // hapus data
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus');
    }
}