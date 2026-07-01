<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Item;
use App\Models\Sale;
use App\Services\Inventory\StockCardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InventorySaleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $items = Item::where('stok', '>', 0)->get();

        if ($customers->isEmpty() || $items->isEmpty()) {
            return;
        }

        // Each scenario mirrors a real kasir transaction: pick 1-2 items,
        // deduct stock, log the stock-out + delivery note, and record a
        // payment that may be full, partial, or none — same as SaleController::store().
        $scenarios = [
            ['bayar_ratio' => 1.0, 'days_ago' => 6],   // lunas
            ['bayar_ratio' => 0.5, 'days_ago' => 4],   // sebagian
            ['bayar_ratio' => 0.0, 'days_ago' => 2],   // belum_bayar
            ['bayar_ratio' => 1.0, 'days_ago' => 1],   // lunas
        ];

        foreach ($scenarios as $index => $scenario) {
            $customer = $customers->random();
            $cartItems = $items->random(min(2, $items->count()));

            $tanggal = Carbon::now()->subDays($scenario['days_ago']);
            $total = 0;
            $cart = [];

            foreach ($cartItems as $item) {
                $available = $item->fresh()->stok;
                $qty = min(2, max((int) floor($available * 0.05), 1));

                if ($qty <= 0 || $qty > $available) {
                    continue;
                }

                $subtotal = $item->harga * $qty;
                $total += $subtotal;

                $cart[] = ['item' => $item, 'qty' => $qty, 'harga' => $item->harga, 'subtotal' => $subtotal];
            }

            if (empty($cart)) {
                continue;
            }

            $bayar = round($total * $scenario['bayar_ratio']);
            $statusPembayaran = $bayar <= 0 ? 'belum_bayar' : ($bayar >= $total ? 'lunas' : 'sebagian');

            $sale = Sale::create([
                'no_faktur' => $this->generateNoFaktur($tanggal, $index),
                'customer_id' => $customer->id,
                'tanggal' => $tanggal,
                'total' => $total,
                'status_pembayaran' => $statusPembayaran,
            ]);

            foreach ($cart as $row) {
                $sale->items()->create([
                    'item_id' => $row['item']->id,
                    'qty' => $row['qty'],
                    'harga' => $row['harga'],
                    'subtotal' => $row['subtotal'],
                ]);
            }

            if ($bayar > 0) {
                $sale->payments()->create([
                    'tanggal' => $tanggal,
                    'jumlah' => $bayar,
                    'keterangan' => 'Pembayaran awal di kasir',
                ]);
            }

            $stockOut = $sale->stockOut()->create([
                'no_transaksi' => $this->generateNoTransaksiKeluar($tanggal),
                'customer_id' => $customer->id,
                'tanggal' => $tanggal,
                'keterangan' => 'Otomatis dari Kasir Penjualan ' . $sale->no_faktur,
            ]);

            foreach ($cart as $row) {
                $item = $row['item'];

                $stockOut->items()->create([
                    'item_id' => $item->id,
                    'unit_id' => $item->satuan_id,
                    'qty_input' => $row['qty'],
                    'qty_base' => $row['qty'],
                ]);

                StockCardService::keluar($item, $row['qty'], $sale->no_faktur);
            }

            DeliveryNote::create([
                'no_surat' => DeliveryNote::generateNoSurat(),
                'stock_out_id' => $stockOut->id,
                'tanggal' => $tanggal,
                'alamat_tujuan' => $customer->alamat,
                'status' => 'draft',
            ]);
        }
    }

    private function generateNoFaktur(Carbon $tanggal, int $sequence): string
    {
        return sprintf('INV-%s%04d', $tanggal->format('Ymd'), $sequence + 1);
    }

    private function generateNoTransaksiKeluar(Carbon $tanggal): string
    {
        $count = \App\Models\StockOut::whereDate('tanggal', $tanggal->toDateString())->count() + 1;

        return sprintf('BRG-KLR-%s-%03d', $tanggal->format('Ymd'), $count);
    }
}
