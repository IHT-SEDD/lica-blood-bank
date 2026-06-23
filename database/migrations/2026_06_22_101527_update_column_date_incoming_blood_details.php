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
        Schema::table('incoming_blood_details', function (Blueprint $table) {
            $table->timestamp('aftap_date')->change();
            $table->timestamp('process_date')->change();
            $table->timestamp('expiry_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_blood_details', function (Blueprint $table) {
            $table->date('aftap_date')->change();
            $table->date('process_date')->change();
            $table->date('expiry_date')->change();
        });
    }
};
