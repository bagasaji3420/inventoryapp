<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;

class InventoryItemTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Kain Katun', 'Kain Sutra', 'Kain Tulle', 'Kain Linen', 'Kain Denim'];

        foreach ($types as $type) {
            ItemType::firstOrCreate(['nama_jenis' => $type]);
        }
    }
}
