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
        Schema::create('blood_stock_log_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->uuid('blood_stock_public_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->string('created_by_user_name')->nullable();
            $table->timestamp('timestamp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_stock_log_activities');
    }
};
