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
            $table->boolean('is_destroyed')->default(false)->after('is_expired');
            $table->timestamp('destroyed_at')->nullable()->after('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('blood_stocks', 'is_destroyed')) {
                $table->dropColumn('is_destroyed');
            }
            if (Schema::hasColumn('blood_stocks', 'destroyed_at')) {
                $table->dropColumn('destroyed_at');
            }
        });
    }
};
