<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->dateTime('tanggal');
            $table->enum('jenis_transaksi', ['masuk', 'keluar', 'opname']);
            $table->string('referensi');
            $table->decimal('masuk', 12, 2)->nullable();
            $table->decimal('keluar', 12, 2)->nullable();
            $table->decimal('saldo', 12, 2);
            $table->timestamps();

            $table->index(['item_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};
