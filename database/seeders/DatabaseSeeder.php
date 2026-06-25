<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Seeder Wilayah Indonesia
        |--------------------------------------------------------------------------
        | Data provinsi, kota, kecamatan, dan desa wajib ada agar dropdown alamat
        | pasien tidak kosong setelah migrate fresh.
        */

        if (DB::table('indonesia_provinces')->count() === 0) {
            Artisan::call('laravolt:indonesia:seed');
        }

        /*
        |--------------------------------------------------------------------------
        | Seeder Data Utama Aplikasi
        |--------------------------------------------------------------------------
        */

        $this->call([
            UserSeeder::class,
            DoctorSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}