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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI APPOINTMENT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DATA PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2)
                ->default(0);

            $table->string('payment_method')
                ->nullable();

            $table->string('proof_image')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS PEMBAYARAN
            |--------------------------------------------------------------------------
            | Status disimpan sebagai string supaya fleksibel.
            |
            | Status yang dipakai:
            | - pending
            | - diterima
            | - ditolak
            */

            $table->string('status', 30)->default('pending');

            /*
            |--------------------------------------------------------------------------
            | VERIFIKASI ADMIN
            |--------------------------------------------------------------------------
            */

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXING
            |--------------------------------------------------------------------------
            */

            $table->index('appointment_id');
            $table->index('status');
            $table->index('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};