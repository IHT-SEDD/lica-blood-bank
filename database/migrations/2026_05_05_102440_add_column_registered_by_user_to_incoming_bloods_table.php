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
            $table->foreignId('registered_by_user_id')
                ->nullable()->after('received_at')
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
        Schema::table('incoming_bloods', function (Blueprint $table) {
            if (Schema::hasColumn('incoming_bloods', 'registered_by_user_id')) {
                $table->dropForeign(['registered_by_user_id']);
                $table->dropColumn('registered_by_user_id');
            }
        });
    }
};
