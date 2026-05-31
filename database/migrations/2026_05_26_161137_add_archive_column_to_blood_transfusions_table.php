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
        Schema::table('blood_transfusions', function (Blueprint $table) {
            $table->timestamp('archived_at')->after('finish_at')->nullable();
            $table->foreignId('archive_by_user_id')
                ->nullable()
                ->after('finish_by_user_id')
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
        Schema::table('blood_transfusions', function (Blueprint $table) {
            if (Schema::hasColumn('blood_transfusions', 'archive_by_user_id')) {
                $table->dropForeign(['archive_by_user_id']);
                $table->dropColumn('archive_by_user_id');
            }
            if (Schema::hasColumn('blood_transfusions', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
        });
    }
};
