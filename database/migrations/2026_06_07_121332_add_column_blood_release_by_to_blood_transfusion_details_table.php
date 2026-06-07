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
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            $table->foreignId('blood_released_by_user_id')->after('blood_release_status')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('blood_received_by')->after('blood_released_by_user_id')->nullable();
            $table->timestamp('blood_released_at')->after('blood_received_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusion_details', 'blood_released_by_user_id')) {
                $table->dropForeign(['blood_released_by_user_id']);
                $table->dropColumn('blood_released_by_user_id');
            }
            if (Schema::hasColumn('blood_transfusion_details', 'blood_received_by')) {
                $table->dropColumn('blood_received_by');
            }
            if (Schema::hasColumn('blood_transfusion_details', 'blood_released_at')) {
                $table->dropColumn('blood_released_at');
            }
        });
    }
};
