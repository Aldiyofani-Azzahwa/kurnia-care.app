<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('appointments')) {
            DB::table('appointments')
                ->where('status', 'diproses')
                ->update(['status' => 'dikonfirmasi']);

            DB::table('appointments')
                ->where('status', 'batal')
                ->update(['status' => 'dibatalkan']);
        }

        if (Schema::hasTable('payments')) {
            DB::table('payments')
                ->where('status', 'diverifikasi')
                ->update(['status' => 'diterima']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('appointments')) {
            DB::table('appointments')
                ->where('status', 'dikonfirmasi')
                ->update(['status' => 'diproses']);

            DB::table('appointments')
                ->where('status', 'dibatalkan')
                ->update(['status' => 'batal']);
        }

        if (Schema::hasTable('payments')) {
            DB::table('payments')
                ->where('status', 'diterima')
                ->update(['status' => 'diverifikasi']);
        }
    }
};