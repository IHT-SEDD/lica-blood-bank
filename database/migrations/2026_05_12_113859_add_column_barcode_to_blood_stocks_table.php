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
            $table->string('barcode_bag_lica_path')->nullable()->after('bag_number_lica');
            $table->string('barcode_bag_lica_filename')->nullable()->after('barcode_bag_lica_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->dropColumn(['barcode_bag_lica_path', 'barcode_bag_lica_filename']);
        });
    }
};
