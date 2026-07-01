<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Services\Inventory\StockCardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InventoryStockInSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::all();
        $items = Item::all();

        if ($suppliers->isEmpty() || $items->isEmpty()) {
            return;
        }

        // Two incoming batches per item, spread across the last 2 weeks,
        // so every item starts with a healthy stock balance above its minimum.
        foreach ($items as $item) {
            foreach ([14, 5] as $daysAgo) {
                $tanggal = Carbon::now()->subDays($daysAgo);
                $qty = $item->stok_minimum * 4;

                $stockIn = StockIn::create([
                    'no_transaksi' => $this->generateNoTransaksi($tanggal),
                    'supplier_id' => $suppliers->random()->id,
                    'tanggal' => $tanggal,
                ]);

                $stockIn->items()->create([
                    'item_id' => $item->id,
                    'unit_id' => $item->satuan_id,
                    'qty_input' => $qty,
                    'qty_base' => $qty,
                ]);

                StockCardService::masuk($item, $qty, $stockIn->no_transaksi);
            }
        }
    }

    private function generateNoTransaksi(Carbon $tanggal): string
    {
        $count = StockIn::whereDate('tanggal', $tanggal->toDateString())->count() + 1;

        return sprintf('BRG-MSK-%s-%03d', $tanggal->format('Ymd'), $count);
    }
}
