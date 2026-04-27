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

            $table->foreignId('incoming_blood_id')
                ->nullable()
                ->constrained('incoming_bloods')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('bag_number')->unique();

            // Golongan darah A, B, O, AB
            $table->enum('blood_group', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['+', '-']);

            // Komponen darah PRC, WB, etc
            $table->string('blood_component');
            $table->integer('blood_volume');

            $table->date('aftap_date');
            // Blood process date
            $table->date('process_date');
            $table->date('expiry_date');

            // 0 = NR, 1 = R
            $table->boolean('is_hiv')->default(false);
            $table->boolean('is_hbsag')->default(false);
            $table->boolean('is_hcv')->default(false);
            $table->boolean('is_syphilis')->default(false);

            $table->boolean('is_expired')->default(false);
            $table->timestamp('used_at')->nullable();

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
