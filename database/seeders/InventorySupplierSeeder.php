<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class InventorySupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['nama_supplier' => 'PT Kain Medan', 'kontak_person' => 'Andi Saputra', 'telepon' => '081234567801', 'alamat' => 'Jl. Industri No. 12, Medan'],
            ['nama_supplier' => 'CV Sutra Nusantara', 'kontak_person' => 'Budi Hartono', 'telepon' => '081234567802', 'alamat' => 'Jl. Tekstil No. 5, Bandung'],
            ['nama_supplier' => 'Toko Kain Jaya', 'kontak_person' => 'Citra Lestari', 'telepon' => '081234567803', 'alamat' => 'Jl. Pasar Baru No. 21, Jakarta'],
            ['nama_supplier' => 'PT Tekstil Sejahtera', 'kontak_person' => 'Dedi Setiawan', 'telepon' => '081234567804', 'alamat' => 'Jl. Industri Tekstil No. 8, Solo'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['nama_supplier' => $supplier['nama_supplier']], $supplier);
        }
    }
}
