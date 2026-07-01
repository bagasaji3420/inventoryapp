<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class InventoryCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['nama_pelanggan' => 'Toko Kain Alisya', 'email' => 'alisya@gmail.com', 'telepon' => '085371722201', 'alamat' => 'Ujung Krawang'],
            ['nama_pelanggan' => 'Toko Susanto', 'email' => 'susanto@gmail.com', 'telepon' => '085371722202', 'alamat' => 'Pulo Gebang'],
            ['nama_pelanggan' => 'Konveksi Rapi Jaya', 'email' => 'rapijaya@gmail.com', 'telepon' => '085371722203', 'alamat' => 'Cikarang'],
            ['nama_pelanggan' => 'Butik Mawar', 'email' => 'mawar@gmail.com', 'telepon' => '085371722204', 'alamat' => 'Bekasi'],
            ['nama_pelanggan' => 'Toko Kain Murah', 'email' => 'kainmurah@gmail.com', 'telepon' => '085371722205', 'alamat' => 'Tangerang'],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['nama_pelanggan' => $customer['nama_pelanggan']], $customer);
        }
    }
}
