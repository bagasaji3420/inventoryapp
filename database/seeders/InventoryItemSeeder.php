<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $meter = Unit::where('nama_satuan', 'Meter')->first();
        $yard = Unit::where('nama_satuan', 'Yard')->first();

        $jenisByName = ItemType::pluck('id', 'nama_jenis');

        $items = [
            ['kode_barang' => 'TULE-HDP-001', 'barcode' => '8991865270015', 'nama_barang' => 'Tulle HDP 001', 'jenis' => 'Kain Tulle', 'satuan' => $yard, 'harga' => 45000, 'stok_minimum' => 15],
            ['kode_barang' => 'KATUN-HDP-002', 'barcode' => '8991865270022', 'nama_barang' => 'Katun HDP 002', 'jenis' => 'Kain Katun', 'satuan' => $meter, 'harga' => 35000, 'stok_minimum' => 50],
            ['kode_barang' => 'SUTRA-HDP-002', 'barcode' => '8991865270039', 'nama_barang' => 'Sutra HDP 002', 'jenis' => 'Kain Sutra', 'satuan' => $meter, 'harga' => 25000, 'stok_minimum' => 100],
            ['kode_barang' => 'LINEN-HDP-003', 'barcode' => '8991865270046', 'nama_barang' => 'Linen HDP 003', 'jenis' => 'Kain Linen', 'satuan' => $meter, 'harga' => 55000, 'stok_minimum' => 30],
            ['kode_barang' => 'DENIM-HDP-004', 'barcode' => '8991865270053', 'nama_barang' => 'Denim HDP 004', 'jenis' => 'Kain Denim', 'satuan' => $yard, 'harga' => 65000, 'stok_minimum' => 20],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(
                ['kode_barang' => $item['kode_barang']],
                [
                    'barcode' => $item['barcode'],
                    'nama_barang' => $item['nama_barang'],
                    'satuan_id' => $item['satuan']->id,
                    'jenis_id' => $jenisByName[$item['jenis']],
                    'harga' => $item['harga'],
                    'stok_minimum' => $item['stok_minimum'],
                    'stok' => 0,
                ]
            );
        }
    }
}
