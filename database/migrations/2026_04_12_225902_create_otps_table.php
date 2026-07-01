<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // relasi ke user
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // kode OTP
            $table->string('code', 225);

            // type OTP (biar fleksibel)
            $table->string('type');
            // contoh: login, reset, verify, 2fa

            // expire time
            $table->timestamp('expires_at');

            // optional (tracking)
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // index biar cepat
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
