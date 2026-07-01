<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class InventoryUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = ['Meter', 'Yard'];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['nama_satuan' => $unit]);
        }
    }
}
