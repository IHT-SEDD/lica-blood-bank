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
        Schema::table('crossmatch_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('blood_transfusion_detail_id')->nullable()->after('id');
                $table->foreign('blood_transfusion_detail_id')->references('id')->on('blood_transfusion_details')->onDelete('set null');
                $table->dropConstrainedForeignId('blood_transfusion_id');
                $table->dropForeign(['blood_transfusion_id']);
                $table->dropColumn('blood_transfusion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crossmatch_histories', function (Blueprint $table) {
                 $table->dropForeign('blood_transfusion_detail_id');
                $table->dropColumn('blood_transfusion_detail_id');
                $table->unsignedBigInteger('blood_transfusion_id')->nullable()->after('id');
                $table->foreign('blood_transfusion_id')->references('id')->on('blood_transfusions')->onDelete('set null');
        });
    }
};
