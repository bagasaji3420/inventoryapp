<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // relasi ke users (1:1)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            // profile data
            $table->string('mobile', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            // optional future
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('pincode')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
