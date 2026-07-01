<?php

namespace App\Exports;

use App\Models\DeliveryNote;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DeliveryNoteExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected ?Carbon $tanggalAwal = null,
        protected ?Carbon $tanggalAkhir = null
    ) {
    }

    public function headings(): array
    {
        return ['#', 'No. Surat Jalan', 'Tanggal', 'Pelanggan', 'Alamat Tujuan', 'Status'];
    }

    public function collection()
    {
        $query = DeliveryNote::with(['stockOut.customer'])->latest('tanggal');

        if ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        }

        if ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        $no = 1;

        return $query->get()->map(fn ($deliveryNote) => [
            $no++,
            $deliveryNote->no_surat,
            $deliveryNote->tanggal->format('Y-m-d'),
            $deliveryNote->stockOut->customer->nama_pelanggan,
            $deliveryNote->alamat_tujuan,
            ucfirst($deliveryNote->status),
        ]);
    }
}
