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
            $table->string('crossmatch_result')->nullable()->after('transfusion_result');
            $table->boolean('blood_release_status')->default(false)->after('transfusion_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusion_details', 'crossmatch_result')) {
                $table->dropColumn('crossmatch_result');
            }
            if (Schema::hasColumn('blood_transfusion_details', 'blood_release_status')) {
                $table->dropColumn('blood_release_status');
            }
        });
    }
};
