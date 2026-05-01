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
        Schema::table('incoming_bloods', function (Blueprint $table) {
            $table->dropUnique(['po_number']);
            $table->string('po_number')->nullable()->change();
            $table->string('batch_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_bloods', function (Blueprint $table) {
            $table->string('po_number')->nullable(false)->change();
            $table->unique('po_number');
            $table->string('batch_number')->nullable(false)->change();
        });
    }
};
