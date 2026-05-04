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
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('incoming_blood_id')
                ->nullable()
                ->constrained('incoming_bloods')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('bag_number')->unique();

            $table->foreignId('blood_pack_id')
                ->nullable()
                ->constrained('blood_packs')
                ->cascadeOnUpdate()
                ->nullOnDelete();

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
        Schema::dropIfExists('blood_stocks');
    }
};
