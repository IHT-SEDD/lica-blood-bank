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
        Schema::create('blood_transfusion_detail_tests', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            
            $table->foreignId('bt_detail_id')
                ->nullable()
                ->constrained('blood_transfusion_details')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 
            
            $table->foreignId('test_id')
                ->nullable()
                ->constrained('tests')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('packages')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 

                   $table->enum('type', ['single', 'package'])->nullable();
            $table->string('result')->nullable();
            $table->string('string_status')->nullable();

            $table->foreignId('result_by_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 
            
            $table->foreignId('verified_by_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 
            
            $table->timestamp('verified_at')->nullable();

            $table->foreignId('validated_by_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete(); 

            $table->timestamp('validated_at')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_transfusion_detail_tests');
    }
};
