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
        Schema::table('blood_destroys', function (Blueprint $table) {
            $table->foreignId('destroyed_by_user_id')->after('reason')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_destroys', function (Blueprint $table) {
            if (Schema::hasColumn('blood_destroys', 'destroyed_by_user_id')) {
                $table->dropForeign(['destroyed_by_user_id']);
                $table->dropColumn('destroyed_by_user_id');
            }
        });
    }
};
