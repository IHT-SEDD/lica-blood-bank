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
            $table->boolean('is_cito')->default(false)->after('is_dct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusions', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusions', 'is_cito')) {
                $table->dropColumn('is_cito');
            }
        });
    }
};
