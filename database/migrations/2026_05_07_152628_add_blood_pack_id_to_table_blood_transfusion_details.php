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
            $table->foreignId('blood_pack_id')
                ->after('blood_transfusion_id')
                ->nullable()
                ->constrained('blood_packs')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_transfusion_details', function (Blueprint $table) {
            $table->dropForeign(['blood_pack_id']);
            $table->dropColumn('blood_pack_id');
        });
    }
};
