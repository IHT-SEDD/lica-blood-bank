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
        Schema::table('blood_transfusions', function (Blueprint $table) {
            $table->foreignId('canceled_by_user_id')->after('deleted_by_user_id')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->text('cancel_reason')->nullable()->after('canceled_by_user_id');
            $table->timestamp('canceled_at')->nullable()->after('cancel_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusions', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusions', 'canceled_by_user_id')) {
                $table->dropForeign(['canceled_by_user_id']);
                $table->dropColumn('canceled_by_user_id');
            }
            if (Schema::hasColumn('blood_transfusions', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
            if (Schema::hasColumn('blood_transfusions', 'canceled_at')) {
                $table->dropColumn('canceled_at');
            }
        });
    }
};
