<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('kode_barang_masuk')->default('BRG-MSK');
            $table->string('kode_barang_keluar')->default('BRG-KLR');
            $table->string('kode_surat_jalan')->default('SJ');
            $table->string('kode_penjualan')->default('INV');
            $table->string('kode_stok_opname')->default('OPN');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'kode_barang_masuk',
                'kode_barang_keluar',
                'kode_surat_jalan',
                'kode_penjualan',
                'kode_stok_opname',
            ]);
        });
    }
};
