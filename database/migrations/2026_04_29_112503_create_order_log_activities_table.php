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
        Schema::create('order_log_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->string('po_number')->nullable();
            $table->string('vendor_name')->nullable();
            $table->json('order_data')->nullable();
            $table->json('order_blood_data')->nullable();
            $table->string('order_by_user_name')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_log_activities');
    }
};
