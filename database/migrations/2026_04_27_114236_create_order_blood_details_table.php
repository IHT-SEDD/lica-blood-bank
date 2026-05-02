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

            $table->foreignId('blood_pack_id')
                ->nullable()
                ->constrained('blood_packs')
                ->cascadeOnUpdate()
                ->nullOnDelete();

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
