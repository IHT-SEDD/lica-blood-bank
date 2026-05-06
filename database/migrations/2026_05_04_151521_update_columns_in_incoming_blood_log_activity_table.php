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
        Schema::table('incoming_blood_log_activities', function (Blueprint $table) {
            $table->renameColumn('blood_stock_data', 'blood_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_blood_log_activities', function (Blueprint $table) {
            $table->renameColumn('blood_data', 'blood_stock_data');
        });
    }
};
