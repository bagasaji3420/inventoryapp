<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $chartMode = $request->input('chart_mode', 'bulanan');
        if (! in_array($chartMode, ['harian', 'bulanan', 'tahunan'], true)) {
            $chartMode = 'bulanan';
        }

        $bulan = $request->input('bulan') ?: Carbon::now()->format('Y-m');
        $tahun = (int) ($request->input('tahun') ?: Carbon::now()->year);

        [$periodFrom, $periodTo, $dateLabels, $periodKeys, $granularity, $periodeLabel] = $this->resolvePeriod(
            $chartMode,
            $bulan,
            $tahun
        );

        $sales = Sale::with('payments')
            ->whereDate('tanggal', '>=', $periodFrom)
            ->whereDate('tanggal', '<=', $periodTo)
            ->get();

        $totalTransaksi = $sales->count();
        $totalOmzet = $sales->sum('total');
        $totalDibayar = $sales->reduce(fn ($carry, $sale) => $carry + $sale->payments->sum('jumlah'), 0);
        $totalPiutang = $totalOmzet - $totalDibayar;

        // Chart 1: omzet per periode (harian/bulanan/tahunan)
        $omzetGroupExpr = $this->groupExpression('tanggal', $granularity);
        $omzetSeries = Sale::whereDate('tanggal', '>=', $periodFrom)
            ->whereDate('tanggal', '<=', $periodTo)
            ->selectRaw("{$omzetGroupExpr} as grp, SUM(total) as total")
            ->groupBy('grp')
            ->get()
            ->pluck('total', 'grp');

        $omzetValues = $this->mapSeriesToKeys($periodKeys, $omzetSeries);

        // Chart 2: barang terlaris (top 5 by qty) dalam periode terpilih
        $barangTerlaris = SaleItem::whereHas('sale', function ($query) use ($periodFrom, $periodTo) {
            $query->whereDate('tanggal', '>=', $periodFrom)->whereDate('tanggal', '<=', $periodTo);
        })
            ->with('item')
            ->selectRaw('item_id, SUM(qty) as total_qty')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $barangLabels = $barangTerlaris->map(fn ($row) => $row->item->nama_barang ?? '-')->values();
        $barangValues = $barangTerlaris->pluck('total_qty')->map(fn ($v) => (float) $v)->values();

        // Chart 3: distribusi status pembayaran dalam periode terpilih
        $statusCounts = [
            'lunas' => $sales->where('status_pembayaran', 'lunas')->count(),
            'sebagian' => $sales->where('status_pembayaran', 'sebagian')->count(),
            'belum_bayar' => $sales->where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Barang Masuk: ringkasan + tren dalam periode terpilih
        $totalMasukTransaksi = StockIn::whereDate('tanggal', '>=', $periodFrom)
            ->whereDate('tanggal', '<=', $periodTo)
            ->count();

        $totalMasukQty = StockInItem::whereHas('stockIn', function ($query) use ($periodFrom, $periodTo) {
            $query->whereDate('tanggal', '>=', $periodFrom)->whereDate('tanggal', '<=', $periodTo);
        })->sum('qty_base');

        $masukGroupExpr = $this->groupExpression('stock_ins.tanggal', $granularity);
        $masukSeries = StockInItem::join('stock_ins', 'stock_ins.id', '=', 'stock_in_items.stock_in_id')
            ->whereDate('stock_ins.tanggal', '>=', $periodFrom)
            ->whereDate('stock_ins.tanggal', '<=', $periodTo)
            ->selectRaw("{$masukGroupExpr} as grp, SUM(stock_in_items.qty_base) as qty")
            ->groupBy('grp')
            ->get()
            ->pluck('qty', 'grp');

        $masukValues = $this->mapSeriesToKeys($periodKeys, $masukSeries);

        // Barang Keluar: ringkasan + tren dalam periode terpilih
        $totalKeluarTransaksi = StockOut::whereDate('tanggal', '>=', $periodFrom)
            ->whereDate('tanggal', '<=', $periodTo)
            ->count();

        $totalKeluarQty = StockOutItem::whereHas('stockOut', function ($query) use ($periodFrom, $periodTo) {
            $query->whereDate('tanggal', '>=', $periodFrom)->whereDate('tanggal', '<=', $periodTo);
        })->sum('qty_base');

        $keluarGroupExpr = $this->groupExpression('stock_outs.tanggal', $granularity);
        $keluarSeries = StockOutItem::join('stock_outs', 'stock_outs.id', '=', 'stock_out_items.stock_out_id')
            ->whereDate('stock_outs.tanggal', '>=', $periodFrom)
            ->whereDate('stock_outs.tanggal', '<=', $periodTo)
            ->selectRaw("{$keluarGroupExpr} as grp, SUM(stock_out_items.qty_base) as qty")
            ->groupBy('grp')
            ->get()
            ->pluck('qty', 'grp');

        $keluarValues = $this->mapSeriesToKeys($periodKeys, $keluarSeries);

        // Surat Jalan: distribusi status dalam periode terpilih
        $deliveryNotes = DeliveryNote::whereDate('tanggal', '>=', $periodFrom)
            ->whereDate('tanggal', '<=', $periodTo)
            ->get();

        $totalSuratJalan = $deliveryNotes->count();

        $suratJalanStatusCounts = [
            'draft' => $deliveryNotes->where('status', 'draft')->count(),
            'terkirim' => $deliveryNotes->where('status', 'terkirim')->count(),
            'selesai' => $deliveryNotes->where('status', 'selesai')->count(),
        ];

        return view('Admin.dashboard', [
            'title' => 'Dashboard',
            'chartMode' => $chartMode,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'periodeLabel' => $periodeLabel,
            'totalTransaksi' => $totalTransaksi,
            'totalOmzet' => $totalOmzet,
            'totalDibayar' => $totalDibayar,
            'totalPiutang' => $totalPiutang,
            'dateLabels' => $dateLabels,
            'omzetValues' => $omzetValues,
            'barangLabels' => $barangLabels,
            'barangValues' => $barangValues,
            'statusCounts' => $statusCounts,
            'totalMasukTransaksi' => $totalMasukTransaksi,
            'totalMasukQty' => $totalMasukQty,
            'masukValues' => $masukValues,
            'totalKeluarTransaksi' => $totalKeluarTransaksi,
            'totalKeluarQty' => $totalKeluarQty,
            'keluarValues' => $keluarValues,
            'totalSuratJalan' => $totalSuratJalan,
            'suratJalanStatusCounts' => $suratJalanStatusCounts,
        ]);
    }

    /**
     * Resolve the single period that drives both the summary cards and the
     * trend charts, based on the chosen mode:
     *  - harian:  semua hari dalam 1 bulan terpilih (input type=month)
     *  - bulanan: semua bulan dalam 1 tahun terpilih (input tahun)
     *  - tahunan: 5 tahun terakhir (tetap, tanpa picker)
     */
    private function resolvePeriod(string $mode, string $bulan, int $tahun): array
    {
        if ($mode === 'tahunan') {
            $from = Carbon::now()->startOfYear()->subYears(4);
            $to = Carbon::now()->endOfYear();

            $labels = [];
            $keys = [];
            for ($year = $from->year; $year <= $to->year; $year++) {
                $labels[] = (string) $year;
                $keys[] = (string) $year;
            }

            return [$from, $to, $labels, $keys, 'year', "{$from->year} - {$to->year}"];
        }

        if ($mode === 'bulanan') {
            $from = Carbon::create($tahun, 1, 1)->startOfYear();
            $to = Carbon::create($tahun, 1, 1)->endOfYear();

            $labels = [];
            $keys = [];
            $cursor = $from->copy();
            while ($cursor->lte($to)) {
                $labels[] = $cursor->format('M Y');
                $keys[] = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            return [$from, $to, $labels, $keys, 'month', "Tahun {$tahun}"];
        }

        // harian: seluruh hari dalam bulan terpilih
        $from = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $to = $from->copy()->endOfMonth();

        $labels = [];
        $keys = [];
        foreach ($from->copy()->daysUntil($to) as $date) {
            $labels[] = $date->format('d M');
            $keys[] = $date->toDateString();
        }

        return [$from, $to, $labels, $keys, 'day', $from->translatedFormat('F Y') ?: $from->format('F Y')];
    }

    private function groupExpression(string $column, string $granularity): string
    {
        return match ($granularity) {
            'month' => "DATE_FORMAT({$column}, '%Y-%m')",
            'year' => "YEAR({$column})",
            default => "DATE({$column})",
        };
    }

    private function mapSeriesToKeys(array $keys, Collection $series): array
    {
        return collect($keys)
            ->map(fn ($key) => (float) ($series[$key] ?? 0))
            ->values()
            ->all();
    }
}
