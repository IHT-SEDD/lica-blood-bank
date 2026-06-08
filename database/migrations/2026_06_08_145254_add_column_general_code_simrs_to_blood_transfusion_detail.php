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
            $table->string('general_code')->nullable()->after('blood_release_at');
        });
        Schema::table('blood_transfusion_detail_tests', function (Blueprint $table) {
            $table->string('general_code')->nullable()->after('result_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('blood_transfusion_details', 'general_code')) {
            Schema::table('blood_transfusion_details', function (Blueprint $table) {
                $table->dropColumn('general_code');
            });
        }

        if (Schema::hasColumn('blood_transfusion_detail_tests', 'general_code')) {
            Schema::table('blood_transfusion_detail_tests', function (Blueprint $table) {
                $table->dropColumn('general_code');
            });
        }
    }
};
