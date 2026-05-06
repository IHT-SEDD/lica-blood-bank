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
        Schema::table('order_log_activities', function (Blueprint $table) {
            $table->renameColumn('order_by_user_name', 'created_by_user_name');
            $table->renameColumn('ordered_at', 'timestamp');
            $table->json('payload')->nullable()->after('order_blood_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_log_activities', function (Blueprint $table) {
            $table->renameColumn('created_by_user_name', 'order_by_user_name');
            $table->renameColumn('timestamp', 'ordered_at');
            $table->dropColumn('payload');
        });
    }
};
