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
        Schema::create('package_tests', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->boolean('is_active')->default(true);
            $table->foreignId('package_id')->constrained()->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('test_id')->constrained()->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_tests');
    }
};
