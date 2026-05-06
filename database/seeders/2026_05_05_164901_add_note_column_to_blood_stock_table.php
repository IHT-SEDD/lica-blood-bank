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
        if(!Schema::hasColumn('incoming_bloods', 'add_new_note')){
               Schema::table('blood_stocks', function (Blueprint $table) {
                    $table->text('add_new_note')->nullable()->after('blood_status');
                });
        }

        if (!Schema::hasColumn('incoming_bloods', 'note')) {
            Schema::table('blood_stocks', function (Blueprint $table) {
                $table->text('note')->nullable()->after('add_new_note');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('incoming_bloods', 'note')) {
                $table->dropColumn('note');
            }
            if (Schema::hasColumn('incoming_bloods', 'add_new_note')) {
                $table->dropColumn('add_new_note');
            }
        });
    }
};
