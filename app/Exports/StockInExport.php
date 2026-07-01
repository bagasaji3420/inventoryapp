<?php

namespace App\Exports;

use App\Models\StockIn;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockInExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected ?Carbon $tanggalAwal = null,
        protected ?Carbon $tanggalAkhir = null
    ) {
    }

    public function headings(): array
    {
        return ['#', 'ID Transaksi', 'Supplier', 'Tanggal', 'Nama Barang', 'Satuan', 'Jumlah Masuk'];
    }

    public function collection()
    {
        $query = StockIn::with(['supplier', 'items.item', 'items.unit'])->latest('tanggal');

        if ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        }

        if ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        $no = 1;
        $rows = collect();

        foreach ($query->get() as $stockIn) {
            foreach ($stockIn->items as $row) {
                $rows->push([
                    $no++,
                    $stockIn->no_transaksi,
                    $stockIn->supplier->nama_supplier,
                    $stockIn->tanggal->format('Y-m-d'),
                    $row->item->nama_barang,
                    $row->unit->nama_satuan,
                    $row->qty_input,
                ]);
            }
        }

        return $rows;
    }
}
