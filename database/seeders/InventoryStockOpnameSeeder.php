<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\StockOpname;
use App\Models\User;
use App\Services\Inventory\StockCardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InventoryStockOpnameSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::first();
        $items = Item::take(2)->get();

        if (! $petugas || $items->isEmpty()) {
            return;
        }

        $tanggal = Carbon::now()->subDay();

        $stockOpname = StockOpname::create([
            'no_opname' => $this->generateNoOpname($tanggal),
            'tanggal_opname' => $tanggal,
            'petugas_id' => $petugas->id,
            'status' => 'selesai',
        ]);

        // Simulate a small physical-count discrepancy: first item is short by 2,
        // second item has 1 extra found in the warehouse.
        $selisihByIndex = [-2, 1];

        foreach ($items as $index => $item) {
            $stokSistem = $item->stok;
            $selisih = $selisihByIndex[$index] ?? 0;
            $stokFisik = max($stokSistem + $selisih, 0);
            $selisih = $stokFisik - $stokSistem;

            $stockOpname->items()->create([
                'item_id' => $item->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $stokFisik,
                'selisih' => $selisih,
            ]);

            if ($selisih != 0) {
                StockCardService::opname($item, $selisih, $stockOpname->no_opname);
            }
        }
    }

    private function generateNoOpname(Carbon $tanggal): string
    {
        $count = StockOpname::whereDate('tanggal_opname', $tanggal->toDateString())->count() + 1;

        return sprintf('OPN%s%04d', $tanggal->format('Ymd'), $count);
    }
}
