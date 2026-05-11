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
        Schema::create('storage_rack_bloods', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('storage_rack_id')
                ->nullable()
                ->constrained('storage_racks')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('blood_stock_id')
                ->nullable()
                ->constrained('blood_stocks')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_rack_bloods');
    }
};
