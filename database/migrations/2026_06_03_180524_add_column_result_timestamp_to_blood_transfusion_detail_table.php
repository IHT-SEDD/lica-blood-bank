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
            $table->timestamp('crossmatch_finish_at')->nullable()->after('crossmatch_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusion_details', 'crossmatch_finish_at')) {
                $table->dropColumn('crossmatch_finish_at');
            }
        });
    }
};
