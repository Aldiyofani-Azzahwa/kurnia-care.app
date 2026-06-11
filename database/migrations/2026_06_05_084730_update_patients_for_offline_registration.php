<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | UBAH user_id MENJADI BOLEH NULL
        |--------------------------------------------------------------------------
        | user_id dipakai untuk pasien online.
        | Untuk pasien offline, user_id boleh kosong.
        */

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();

            $table->foreignId('registered_by_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('registration_type', ['online', 'offline'])
                ->default('online')
                ->after('registered_by_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['registered_by_id']);
            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'registered_by_id',
                'registration_type',
            ]);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};