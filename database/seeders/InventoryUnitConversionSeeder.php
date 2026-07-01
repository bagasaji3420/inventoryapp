<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Database\Seeder;

class InventoryUnitConversionSeeder extends Seeder
{
    public function run(): void
    {
        $meter = Unit::where('nama_satuan', 'Meter')->first();
        $yard = Unit::where('nama_satuan', 'Yard')->first();

        if (! $meter || ! $yard) {
            return;
        }

        UnitConversion::updateOrCreate(
            ['from_unit_id' => $yard->id, 'to_unit_id' => $meter->id],
            ['factor' => 0.9144]
        );

        UnitConversion::updateOrCreate(
            ['from_unit_id' => $meter->id, 'to_unit_id' => $yard->id],
            ['factor' => 1.0936]
        );
    }
}
