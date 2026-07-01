<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaleExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected ?Carbon $tanggalAwal = null,
        protected ?Carbon $tanggalAkhir = null
    ) {
    }

    public function headings(): array
    {
        return ['#', 'No. Faktur', 'Tanggal', 'Pelanggan', 'Total', 'Status Pembayaran'];
    }

    public function collection()
    {
        $query = Sale::with(['customer'])->latest('tanggal');

        if ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        }

        if ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        $no = 1;

        return $query->get()->map(fn ($sale) => [
            $no++,
            $sale->no_faktur,
            $sale->tanggal->format('Y-m-d'),
            $sale->customer->nama_pelanggan,
            $sale->total,
            ucfirst(str_replace('_', ' ', $sale->status_pembayaran)),
        ]);
    }
}
