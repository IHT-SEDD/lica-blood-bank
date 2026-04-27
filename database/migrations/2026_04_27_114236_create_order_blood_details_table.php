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
        Schema::create('order_blood_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('order_blood_id')
                ->constrained('order_bloods')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Golongan darah A, B, O, AB
            $table->enum('blood_group', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['+', '-']);

            // Komponen darah PRC, WB, etc
            $table->string('blood_component');
            $table->integer('blood_volume');

            // 0 = NR, 1 = R
            $table->boolean('is_hiv')->default(false);
            $table->boolean('is_hbsag')->default(false);
            $table->boolean('is_hcv')->default(false);
            $table->boolean('is_syphilis')->default(false);

            $table->unsignedInteger('quantity');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_blood_details');
    }
};
