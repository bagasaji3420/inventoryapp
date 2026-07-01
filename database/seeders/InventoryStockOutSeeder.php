<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Item;
use App\Models\StockOut;
use App\Services\Inventory\StockCardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InventoryStockOutSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $items = Item::all();

        if ($customers->isEmpty() || $items->isEmpty()) {
            return;
        }

        // Take out a modest slice of each item's current stock so the item
        // still stays above its minimum afterwards (no negative balances).
        foreach ($items as $item) {
            $qty = (int) floor($item->stok * 0.2);

            if ($qty <= 0) {
                continue;
            }

            $tanggal = Carbon::now()->subDays(3);

            $stockOut = StockOut::create([
                'no_transaksi' => $this->generateNoTransaksi($tanggal),
                'customer_id' => $customers->random()->id,
                'tanggal' => $tanggal,
                'keterangan' => 'Pengeluaran rutin',
            ]);

            $stockOut->items()->create([
                'item_id' => $item->id,
                'unit_id' => $item->satuan_id,
                'qty_input' => $qty,
                'qty_base' => $qty,
            ]);

            StockCardService::keluar($item, $qty, $stockOut->no_transaksi);
        }
    }

    private function generateNoTransaksi(Carbon $tanggal): string
    {
        $count = StockOut::whereDate('tanggal', $tanggal->toDateString())->count() + 1;

        return sprintf('BRG-KLR-%s-%03d', $tanggal->format('Ymd'), $count);
    }
}
