<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE medical_notes
            MODIFY action_status ENUM('berhasil', 'perlu_kontrol', 'gagal', 'lainnya')
            NOT NULL
            DEFAULT 'berhasil'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE medical_notes
            MODIFY action_status ENUM('pending', 'selesai')
            NOT NULL
            DEFAULT 'pending'
        ");
    }
};