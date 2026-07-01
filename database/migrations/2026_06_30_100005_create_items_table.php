<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('barcode')->unique();
            $table->string('nama_barang');
            $table->foreignId('satuan_id')->constrained('units');
            $table->foreignId('jenis_id')->constrained('item_types');
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('stok_minimum', 12, 2)->default(0);
            $table->decimal('stok', 12, 2)->default(0);
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
