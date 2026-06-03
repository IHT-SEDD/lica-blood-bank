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
        Schema::table('blood_packs', function (Blueprint $table) {
            $table->integer('storage_temperature_from')->nullable()->after('danger_quantity');
            $table->integer('storage_temperature_to')->nullable()->after('storage_temperature_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_packs', function (Blueprint $table) {
            if (Schema::hasColumn('blood_packs', 'storage_temperature_from')) {
                $table->dropColumn('storage_temperature_from');
            }
            if (Schema::hasColumn('blood_packs', 'storage_temperature_to')) {
                $table->dropColumn('storage_temperature_to');
            }
        });
    }
};
