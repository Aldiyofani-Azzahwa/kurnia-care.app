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

            /*
            |--------------------------------------------------------------------------
            | RELASI
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | DATA BOOKING
            |--------------------------------------------------------------------------
            */

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
                'syrup',
            ]);

            // Paket khitan
            $table->string('circumcision_package');

            /*
            |--------------------------------------------------------------------------
            | STATUS APPOINTMENT
            |--------------------------------------------------------------------------
            | Status disimpan sebagai string supaya fleksibel dan tidak error
            | saat ada penyesuaian status baru.
            |
            | Status yang dipakai:
            | - menunggu
            | - dikonfirmasi
            | - selesai
            | - dibatalkan
            */

            $table->string('status', 30)->default('menunggu');

            // Catatan admin
            $table->text('admin_note')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXING
            |--------------------------------------------------------------------------
            */

            $table->index('appointment_date');
            $table->index('status');
            $table->index(['doctor_id', 'appointment_date', 'appointment_time'], 'appointments_doctor_date_time_index');
            $table->index(['patient_id', 'appointment_date'], 'appointments_patient_date_index');
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