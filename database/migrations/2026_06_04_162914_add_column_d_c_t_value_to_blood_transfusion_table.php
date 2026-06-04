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
            $table->string('dct_value')->nullable()->after('is_dct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusions', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusions', 'dct_value')) {
                $table->dropColumn('dct_value');
            }
        });
    }
};
