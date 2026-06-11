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
        Schema::create('appointments', function (Blueprint $table) {

            $table->id();

            // Relasi pasien
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // Relasi dokter
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            // Relasi layanan
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Relasi jadwal
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('schedules')
                ->nullOnDelete();

            // Tanggal booking
            $table->date('appointment_date');

            // Hari booking
            $table->string('appointment_day');

            // Jam booking
            $table->time('appointment_time');

            // Jenis obat
            $table->enum('medicine_type', [
                'puyer',
                'tablet',
                'syrup'
            ]);

            // Paket khitan
            $table->string('circumcision_package');

            // Status tindakan
            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai',
                'batal'
            ])->default('menunggu');

            // Catatan admin
            $table->text('admin_note')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXING
            |--------------------------------------------------------------------------
            */

            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('service_id');
            $table->index('schedule_id');

            $table->index('appointment_date');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};