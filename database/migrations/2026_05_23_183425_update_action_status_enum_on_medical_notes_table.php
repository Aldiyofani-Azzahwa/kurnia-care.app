<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE medical_notes
                MODIFY action_status ENUM('berhasil', 'perlu_kontrol', 'gagal', 'lainnya')
                NOT NULL
                DEFAULT 'berhasil'
            ");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("
                ALTER TABLE medical_notes
                DROP CONSTRAINT IF EXISTS medical_notes_action_status_check
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status DROP DEFAULT
            ");

            DB::statement("
                UPDATE medical_notes
                SET action_status = 'berhasil'
                WHERE action_status IN ('menunggu', 'dikonfirmasi', 'selesai', 'pending')
                   OR action_status IS NULL
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status TYPE VARCHAR(50)
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status SET DEFAULT 'berhasil'
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status SET NOT NULL
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ADD CONSTRAINT medical_notes_action_status_check
                CHECK (action_status IN ('berhasil', 'perlu_kontrol', 'gagal', 'lainnya'))
            ");

            return;
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE medical_notes
                MODIFY action_status ENUM('pending', 'selesai')
                NOT NULL
                DEFAULT 'pending'
            ");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("
                ALTER TABLE medical_notes
                DROP CONSTRAINT IF EXISTS medical_notes_action_status_check
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status DROP DEFAULT
            ");

            DB::statement("
                UPDATE medical_notes
                SET action_status = 'pending'
                WHERE action_status IN ('berhasil', 'perlu_kontrol', 'gagal', 'lainnya')
                   OR action_status IS NULL
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status TYPE VARCHAR(50)
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status SET DEFAULT 'pending'
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ALTER COLUMN action_status SET NOT NULL
            ");

            DB::statement("
                ALTER TABLE medical_notes
                ADD CONSTRAINT medical_notes_action_status_check
                CHECK (action_status IN ('pending', 'selesai'))
            ");

            return;
        }
    }
};