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
            if (Schema::hasColumn('schedules', 'schedule_date')) {
                $table->dropIndex(['schedule_date']);
            }
            if (Schema::hasColumn('schedules', 'is_available')) {
                $table->dropIndex(['is_available']);
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'schedule_date')) {
                $table->dropColumn('schedule_date');
            }
            if (Schema::hasColumn('schedules', 'is_available')) {
                $table->dropColumn('is_available');
            }
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
            if (Schema::hasColumn('schedules', 'day')) {
                $table->dropColumn('day');
            }
            if (!Schema::hasColumn('schedules', 'schedule_date')) {
                $table->date('schedule_date')->nullable()->after('doctor_id');
            }
            if (!Schema::hasColumn('schedules', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('quota_per_day');
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index('schedule_date');
            $table->index('is_available');
        });
    }
};
