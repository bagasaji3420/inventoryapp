<?php

namespace Database\Seeders;

use App\Models\DeliveryNote;
use App\Models\StockOut;
use Illuminate\Database\Seeder;

class InventoryDeliveryNoteSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['draft', 'terkirim', 'selesai'];

        $stockOuts = StockOut::whereDoesntHave('deliveryNote')
            ->with('customer')
            ->get();

        foreach ($stockOuts as $index => $stockOut) {
            DeliveryNote::create([
                'no_surat' => DeliveryNote::generateNoSurat(),
                'stock_out_id' => $stockOut->id,
                'tanggal' => $stockOut->tanggal,
                'alamat_tujuan' => $stockOut->customer->alamat,
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }
}
