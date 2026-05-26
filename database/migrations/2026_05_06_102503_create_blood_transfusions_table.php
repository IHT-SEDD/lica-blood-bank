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
        Schema::create('blood_transfusions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('patients')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('insurance_id')
                ->nullable()
                ->constrained('insurances')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('lab_number')->nullable();
            $table->string('order_number')->nullable();
            $table->string('relation_name')->nullable();
            $table->string('relation_type')->nullable();
            $table->timestamp('blood_request_at');
            $table->text('diagnosis')->nullable();
            $table->timestamp('finish_at')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('blood_quantity');

            $table->foreignId('checkin_by_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('finish_by_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('deleted_by_user_id')
                ->nullable()
                ->constrained('users')
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
        Schema::dropIfExists('blood_transfusions');
    }
};
