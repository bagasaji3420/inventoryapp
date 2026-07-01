<?php

namespace App\Exports;

use App\Models\Item;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockCardItemExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        protected Item $item,
        protected ?Carbon $tanggalAwal = null,
        protected ?Carbon $tanggalAkhir = null
    ) {}

    public function title(): string
    {
        return 'Kartu Stok';
    }

    public function headings(): array
    {
        return ['#', 'Tanggal', 'Jenis Transaksi', 'Referensi', 'Masuk', 'Keluar', 'Saldo'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF222222']], 'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']]],
        ];
    }

    public function collection()
    {
        $query = $this->item->stockCards()->orderBy('tanggal', 'desc');

        if ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        return $query->get()->values()->map(fn ($sc, $i) => [
            $i + 1,
            \Carbon\Carbon::parse($sc->tanggal)->format('Y-m-d H:i'),
            ucfirst($sc->jenis_transaksi),
            $sc->referensi,
            $sc->masuk  ?? '-',
            $sc->keluar ?? '-',
            $sc->saldo,
        ]);
    }
}
