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
            $table->boolean('is_print_incompatible_letter')->default(false)->after('crossmatch_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusion_details', 'is_print_incompatible_letter')) {
                $table->dropColumn('is_print_incompatible_letter');
            }
        });
    }
};
