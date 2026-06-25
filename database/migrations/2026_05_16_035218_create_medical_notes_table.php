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
        Schema::create('medical_notes', function (Blueprint $table) {

            $table->id();

            // Relasi appointment
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            // Relasi dokter
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            // Catatan tindakan
            $table->text('note');

            // Status tindakan
            $table->enum('action_status', [
                'menunggu',
                'dikonfirmasi',
                'selesai'
            ])->default('menunggu');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('appointment_id');
            $table->index('doctor_id');
            $table->index('action_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};