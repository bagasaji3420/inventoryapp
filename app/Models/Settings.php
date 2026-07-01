<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'kode_barang_masuk',
        'kode_barang_keluar',
        'kode_surat_jalan',
        'kode_penjualan',
        'kode_stok_opname',
        'nama_perusahaan',
        'alamat_perusahaan',
        'logo',
    ];

    /**
     * Settings is a single-row table; fetch it (creating the default row
     * the first time) instead of querying by id everywhere.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
