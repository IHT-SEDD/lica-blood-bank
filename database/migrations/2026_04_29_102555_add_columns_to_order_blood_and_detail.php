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
        Schema::table('order_bloods', function (Blueprint $table) {
            $table->text('description')->nullable()->after('total_quantity');

            $table->foreignId('ordered_by_user_id')
                ->nullable()->after('description')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        Schema::table('order_blood_details', function (Blueprint $table) {
            $table->text('note')->nullable()->after('blood_volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_bloods', function (Blueprint $table) {
            if (Schema::hasColumn('order_bloods', 'ordered_by_user_id')) {
                $table->dropForeign(['ordered_by_user_id']);
                $table->dropColumn('ordered_by_user_id');
            }

            if (Schema::hasColumn('order_bloods', 'description')) {
                $table->dropColumn('description');
            }
        });

        Schema::table('order_blood_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_blood_details', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};
