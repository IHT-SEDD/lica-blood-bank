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
        Schema::table('transfusion_reactions', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->string('level')->nullable()->after('category');
            $table->integer('time_begin')->nullable()->after('level');
            $table->integer('time_end')->nullable()->after('time_begin');
            $table->text('indicator')->nullable()->after('time_end');
            $table->string('general_code')->nullable()->after('indicator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfusion_reactions', function (Blueprint $table) {
            if (Schema::hasColumn('transfusion_reactions', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('transfusion_reactions', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('transfusion_reactions', 'time_begin')) {
                $table->dropColumn('time_begin');
            }
            if (Schema::hasColumn('transfusion_reactions', 'time_end')) {
                $table->dropColumn('time_end');
            }
            if (Schema::hasColumn('transfusion_reactions', 'indicator')) {
                $table->dropColumn('indicator');
            }
            if (Schema::hasColumn('transfusion_reactions', 'general_code')) {
                $table->dropColumn('general_code');
            }
        });
    }
};
