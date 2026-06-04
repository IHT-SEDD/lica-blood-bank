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
        Schema::create('simrs_configs', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('key', 100)->unique()->comment('Identifier unik config, e.g. hasil_insert_url');
            $table->string('label', 150)->nullable()->comment('Label deskriptif untuk UI/admin');
            $table->text('value')->comment('Nilai config (URL, API key, timeout, dll)');
            $table->string('group', 100)->nullable()->comment('Grup config, e.g: endpoint, header, timeout');
            $table->text('description')->nullable()->comment('Keterangan tambahan');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simrs_configs');
    }
};
