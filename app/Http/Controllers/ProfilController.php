<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $data = [
            "nama" => "Asykaril kafifulloh",
            "nim" => "4124048",
            "prodi" => "sistem informasi",
            "semester" => "4",
            "keahlian" => ["Laravel", "Java", "MySQL", "Git"]
        ];

        return view('profil', $data);
    }

    public function show($nim)
    {
        return "<h1>Profil Mahasiswa dengan NIM: 4124048 </h1>";
    }
}