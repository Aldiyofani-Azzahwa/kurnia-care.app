<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            // Hapus kolom lama jika ada
            if (Schema::hasColumn('schedules', 'schedule_date')) {
                $table->dropColumn('schedule_date');
            }

            if (Schema::hasColumn('schedules', 'is_available')) {
                $table->dropColumn('is_available');
            }

            // Tambah kolom baru
            if (!Schema::hasColumn('schedules', 'day')) {
                $table->string('day')->after('doctor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            // Hapus kolom day jika ada
            if (Schema::hasColumn('schedules', 'day')) {
                $table->dropColumn('day');
            }

            // Kembalikan kolom lama jika belum ada
            if (!Schema::hasColumn('schedules', 'schedule_date')) {
                $table->date('schedule_date')->nullable()->after('doctor_id');
            }

            if (!Schema::hasColumn('schedules', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('quota_per_day');
            }
        });

        // Tambahkan index kembali
        Schema::table('schedules', function (Blueprint $table) {

            try {
                $table->index('schedule_date');
            } catch (\Exception $e) {
                // abaikan jika index sudah ada
            }

            try {
                $table->index('is_available');
            } catch (\Exception $e) {
                // abaikan jika index sudah ada
            }
        });
    }
};