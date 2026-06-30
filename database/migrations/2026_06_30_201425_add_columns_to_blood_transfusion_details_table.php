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
            $table->timestamp('print_incompatible_letter_at')->nullable()->after('is_print_incompatible_letter');
            $table->timestamp('approve_incompatible_at')->nullable()->after('is_approval_incompatible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusion_details', 'print_incompatible_letter_at')) {
                $table->dropColumn('print_incompatible_letter_at');
            }
            if (Schema::hasColumn('blood_transfusion_details', 'approve_incompatible_at')) {
                $table->dropColumn('approve_incompatible_at');
            }
        });
    }
};
