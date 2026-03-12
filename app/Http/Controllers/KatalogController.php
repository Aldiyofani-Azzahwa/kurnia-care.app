<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index()
    {
        $produk = [
            ["nama" => "Laptop", "harga" => 8000000],
            ["nama" => "Mouse", "harga" => 150000],
            ["nama" => "Keyboard", "harga" => 300000],
            ["nama" => "Monitor", "harga" => 2000000],
            ["nama" => "Flashdisk", "harga" => 100000],
        ];

        return view('katalog.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = [
            ["nama" => "Laptop", "harga" => 8000000],
            ["nama" => "Mouse", "harga" => 150000],
            ["nama" => "Keyboard", "harga" => 300000],
            ["nama" => "Monitor", "harga" => 2000000],
            ["nama" => "Flashdisk", "harga" => 100000],
        ];

        $item = $produk[$id];

        return "<h1>{$item['nama']}</h1><p>Harga: Rp {$item['harga']}</p>";
    }
}