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
            $table->string('component')->nullable()->after('blood_stock_id');
            $table->renameColumn('transfusion_text', 'transfusion_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            $table->dropColumn('component');
            $table->renameColumn('transfusion_result', 'transfusion_text');
        });
    }
};
