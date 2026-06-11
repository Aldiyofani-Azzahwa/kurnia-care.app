<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Paket Khitan Standar',
                'description' => 'Layanan khitan modern standar.',
                'price' => 750000,
                'duration_minutes' => 45,
            ],
            [
                'name' => 'Paket Khitan Premium',
                'description' => 'Layanan khitan modern premium.',
                'price' => 1250000,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Paket Khitan Anak Gemuk',
                'description' => 'Layanan khusus anak dengan berat badan lebih.',
                'price' => 1500000,
                'duration_minutes' => 75,
            ],
        ];

        foreach ($services as $service) {

            Service::create([
                'name' => $service['name'],
                'slug' => Str::slug($service['name']),
                'description' => $service['description'],
                'price' => $service['price'],
                'duration_minutes' => $service['duration_minutes'],
                'is_active' => true,
            ]);

        }
    }
}