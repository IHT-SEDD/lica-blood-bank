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
        Schema::create('incoming_blood_log_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->uuid('incoming_blood_public_id')->nullable();
            $table->string('po_number')->nullable();
            $table->string('batch_number')->nullable();
            $table->json('incoming_data')->nullable();
            $table->json('blood_stock_data')->nullable();
            $table->string('status')->nullable();
            $table->string('created_by_user_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_blood_log_activities');
    }
};
