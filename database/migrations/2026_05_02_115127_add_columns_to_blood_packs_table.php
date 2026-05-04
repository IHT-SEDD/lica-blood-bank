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
            $table->integer('warning_quantity')->after('blood_component');
            $table->integer('danger_quantity')->after('warning_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_packs', function (Blueprint $table) {
            if (Schema::hasColumn('blood_packs', 'warning_quantity')) {
                $table->dropColumn('warning_quantity');
            }
            if (Schema::hasColumn('blood_packs', 'danger_quantity')) {
                $table->dropColumn('danger_quantity');
            }
        });
    }
};
