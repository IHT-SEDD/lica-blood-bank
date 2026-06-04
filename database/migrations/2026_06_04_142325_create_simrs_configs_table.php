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

        DB::table('simrs_config')->insert([
            // --- Endpoint
            [
                'key' => 'hasil_insert_url',
                'label' => 'URL Insert Hasil ke SIMRS',
                'value' => 'http://192.168.71.2/webservice/lica/hasil/insert',
                'group' => 'endpoint',
                'description' => 'Endpoint SIMRS untuk mengirim hasil transfusi darah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // --- Headers
            [
                'key' => 'hasil_insert_api_key',
                'label' => 'Header: x-api-key',
                'value' => 'licaapi',
                'group' => 'header',
                'description' => 'Nama header API key yang dikirim ke SIMRS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'hasil_insert_api_key_value',
                'label' => 'Nilai: x-api-key',
                'value' => 'keyapi',
                'group' => 'header',
                'description' => 'Nilai API key untuk header x-api-key',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'hasil_insert_key_ws',
                'label' => 'Header: key-ws',
                'value' => 'kKbau319',
                'group' => 'header',
                'description' => 'Nilai key-ws untuk header ke SIMRS',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // --- Timeout
            [
                'key' => 'hasil_insert_timeout',
                'label' => 'Timeout (detik)',
                'value' => '30',
                'group' => 'timeout',
                'description' => 'Timeout HTTP request ke SIMRS dalam detik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simrs_configs');
    }
};
