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
            $table->string('po_file_path')->nullable()->after('status');
            $table->string('po_file_name')->nullable()->after('po_file_path');

            $table->unsignedInteger('po_file_print_count')->default(0)->after('po_file_name');
            $table->unsignedInteger('po_file_download_count')->default(0)->after('po_file_print_count');
        });

        Schema::table('order_log_activities', function (Blueprint $table) {
            $table->string('po_file_path')->nullable()->after('deleted_at');
            $table->string('po_file_name')->nullable()->after('po_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_bloods', function (Blueprint $table) {
            $table->dropColumn([
                'po_file_path',
                'po_file_name',
                'po_file_print_count',
                'po_file_download_count'
            ]);
        });

        Schema::table('order_log_activities', function (Blueprint $table) {
            $table->dropColumn([
                'po_file_path',
                'po_file_name'
            ]);
        });
    }
};
