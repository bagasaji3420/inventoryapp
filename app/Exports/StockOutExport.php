<?php

namespace App\Exports;

use App\Models\StockOut;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockOutExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected ?Carbon $tanggalAwal = null,
        protected ?Carbon $tanggalAkhir = null
    ) {
    }

    public function headings(): array
    {
        return ['#', 'ID Transaksi', 'Tanggal', 'Nama Pelanggan', 'Nama Barang', 'Satuan', 'Jumlah Keluar', 'Keterangan'];
    }

    public function collection()
    {
        $query = StockOut::with(['customer', 'items.item', 'items.unit'])->latest('tanggal');

        if ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        }

        if ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        $no = 1;
        $rows = collect();

        foreach ($query->get() as $stockOut) {
            foreach ($stockOut->items as $row) {
                $rows->push([
                    $no++,
                    $stockOut->no_transaksi,
                    $stockOut->tanggal->format('Y-m-d'),
                    $stockOut->customer->nama_pelanggan,
                    $row->item->nama_barang,
                    $row->unit->nama_satuan,
                    $row->qty_input,
                    $stockOut->keterangan,
                ]);
            }
        }

        return $rows;
    }
}
