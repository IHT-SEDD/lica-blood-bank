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
            $table->foreignId('storage_rack_id')
                ->after('blood_pack_id')
                ->nullable()
                ->constrained('storage_racks')
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
            if (Schema::hasColumn('blood_stocks', 'storage_rack_id')) {
                $table->dropForeign(['storage_rack_id']);
                $table->dropColumn('storage_rack_id');
            }
        });
    }
};
