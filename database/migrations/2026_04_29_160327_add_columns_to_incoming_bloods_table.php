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
            $table->string('status')->default('stock_registered')->after('batch_number');

            $table->foreignId('received_by_user_id')
                ->nullable()->after('status')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('received_at')->nullable()->after('received_by_user_id');
            $table->timestamp('stock_ready_at')->nullable()->after('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_bloods', function (Blueprint $table) {
            if (Schema::hasColumn('incoming_bloods', 'received_by_user_id')) {
                $table->dropForeign(['received_by_user_id']);
                $table->dropColumn('received_by_user_id');
            }

            if (Schema::hasColumn('incoming_bloods', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('incoming_bloods', 'received_at')) {
                $table->dropColumn('received_at');
            }

            if (Schema::hasColumn('incoming_bloods', 'stock_ready_at')) {
                $table->dropColumn('stock_ready_at');
            }
        });
    }
};
