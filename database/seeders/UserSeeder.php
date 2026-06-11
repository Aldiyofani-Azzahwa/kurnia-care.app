<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Administrator Kurnia Care',
            'email' => 'admin@kurniacare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DOKTER
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'dr. Ahmad Kurnia',
            'email' => 'dokter@kurniacare.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'phone' => '081222333444',
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER / PASIEN
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Pasien Demo',
            'email' => 'pasien@kurniacare.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081555666777',
        ]);
    }
}