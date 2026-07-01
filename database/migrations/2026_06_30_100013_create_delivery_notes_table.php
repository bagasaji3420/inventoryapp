<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->foreignId('stock_out_id')->constrained('stock_outs')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('alamat_tujuan')->nullable();
            $table->enum('status', ['draft', 'terkirim', 'selesai'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
