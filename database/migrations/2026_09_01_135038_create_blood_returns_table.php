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
        Schema::create('blood_returns', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('blood_stock_id')->constrained('blood_stocks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('blood_transfusion_detail_id')->nullable()
                ->constrained('blood_transfusion_details')->nullOnDelete()->cascadeOnUpdate();
            $table->string('returned_from_status');
            $table->text('reason_return');
            $table->foreignId('return_by_user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('return_by_user_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_returns');
    }
};
