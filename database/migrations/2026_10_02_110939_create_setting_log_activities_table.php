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
        Schema::create('setting_log_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('setting_key', 100);
            $table->string('action', 50)->default('updated');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();

            // Pelaku (user_name disimpan sebagai snapshot, karena user bisa diubah/dihapus)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();

            // Jejak request
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Log bersifat append-only, jadi tidak ada updated_at
            $table->timestamp('created_at')->useCurrent();
            $table->index('setting_key');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_log_activities');
    }
};
