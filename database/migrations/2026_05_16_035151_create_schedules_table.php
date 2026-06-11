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
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->date('schedule_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->integer('quota_per_day')
                ->default(10);

            $table->boolean('is_available')
                ->default(true);

            $table->timestamps();

            $table->index('doctor_id');
            $table->index('schedule_date');
            $table->index('is_available');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};