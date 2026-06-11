<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorUser = User::where('role', 'dokter')->first();

        Doctor::create([
            'user_id' => $doctorUser->id,
            'name' => 'dr. Ahmad Kurnia',
            'specialist' => 'Dokter Khitan Modern',
            'sip_number' => 'SIP-001/KC/2026',
            'phone' => '081222333444',
            'bio' => 'Dokter berpengalaman dalam layanan khitan modern.',
            'is_active' => true,
        ]);
    }
}