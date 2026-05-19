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
        Schema::create('crossmatch_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('blood_stock_id')
                ->nullable()
                ->constrained('blood_stocks')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('blood_transfusion_id')
                ->nullable()
                ->constrained('blood_transfusions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('patient_name')->nullable();

            $table->string('result')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crossmatch_histories');
    }
};
