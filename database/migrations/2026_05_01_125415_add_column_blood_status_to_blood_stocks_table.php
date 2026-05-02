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
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->string('blood_status')->nullable()->after('is_expired');
            $table->string('bag_number_lica')->nullable()->unique()->after('bag_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('blood_stocks', 'blood_status')) {
                $table->dropColumn('blood_status');
            }
            if (Schema::hasColumn('blood_stocks', 'bag_number_lica')) {
                $table->dropUnique(['bag_number_lica']);
                $table->dropColumn('bag_number_lica');
            }
        });
    }
};
