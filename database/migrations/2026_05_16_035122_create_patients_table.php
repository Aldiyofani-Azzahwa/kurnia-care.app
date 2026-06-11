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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Identitas Anak
            $table->string('child_name');
            $table->integer('child_age');
            $table->decimal('child_weight', 5, 2);

            $table->text('drug_allergy')->nullable();
            $table->text('bleeding_history')->nullable();
            $table->text('surgery_history')->nullable();
            $table->text('disease_history')->nullable();

            $table->text('address');

            // Identitas Orang Tua
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('phone', 30);

            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();

            $table->enum('information_source', [
                'Instagram',
                'Facebook',
                'Google',
                'Lainnya'
            ])->nullable();

            // Foto anak
            $table->string('child_photo')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('phone');
            $table->index('child_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};