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
            $table->dropForeign(['incoming_blood_id']);
            $table->dropColumn('incoming_blood_id');

            $table->foreignId('incoming_blood_detail_id')
                ->nullable()->after('bag_number_lica')
                ->constrained('incoming_blood_details')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->dropForeign(['incoming_blood_detail_id']);
            $table->dropColumn('incoming_blood_detail_id');

            $table->foreignId('incoming_blood_id')
                ->nullable()
                ->constrained('incoming_bloods')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
