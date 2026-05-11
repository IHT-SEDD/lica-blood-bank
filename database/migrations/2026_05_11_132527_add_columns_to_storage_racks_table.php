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
        Schema::table('storage_racks', function (Blueprint $table) {
            $table->string('rack_type')->default('blood')->after('name');
            $table->enum('blood_group', ['A', 'B', 'AB', 'O'])->nullable()->after('rack_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_racks', function (Blueprint $table) {
            if (Schema::hasColumn('storage_racks', 'rack_type')) {
                $table->dropColumn('rack_type');
            }
            if (Schema::hasColumn('storage_racks', 'blood_group')) {
                $table->dropColumn('blood_group');
            }
        });
    }
};
