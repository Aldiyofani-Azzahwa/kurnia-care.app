<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('province_code')->nullable()->after('address');
            $table->string('province_name')->nullable()->after('province_code');

            $table->string('city_code')->nullable()->after('province_name');
            $table->string('city_name')->nullable()->after('city_code');

            $table->string('district_code')->nullable()->after('city_name');
            $table->string('district_name')->nullable()->after('district_code');

            $table->string('village_code')->nullable()->after('district_name');
            $table->string('village_name')->nullable()->after('village_code');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'province_code',
                'province_name',
                'city_code',
                'city_name',
                'district_code',
                'district_name',
                'village_code',
                'village_name',
            ]);
        });
    }
};