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
        Schema::create('blood_packs', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            // Golongan darah A, B, O, AB
            $table->enum('blood_group', ['A', 'B', 'AB', 'O']);
            $table->enum('blood_rhesus', ['+', '-']);

            // Komponen darah PRC, WB, etc
            $table->string('blood_component');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_packs');
    }
};
