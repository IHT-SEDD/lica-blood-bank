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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('blood_group')->nullable()->after('address')->change();
            $table->string('blood_rhesus')->nullable()->after('blood_group')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
             $table->string('blood_group')->nullable(false)->after('address')->change();
            $table->string('blood_rhesus')->nullable(false)->after('blood_group')->change();
        });
    }
};
