<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\StockCardItemExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Settings;
use App\Models\StockCard;
use App\Services\CachedPaginationService;
use App\Services\CacheVersionService;
use App\Services\Inventory\StockCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockCardController extends Controller
{
    public function index()
    {
        $startOfMonth = now()->startOfMonth();

        $items = Item::with([
            'satuan',
            'stockCards' => fn ($q) => $q->where('tanggal', '>=', $startOfMonth),
        ])->orderBy('nama_barang')->paginate(4);

        return view('Admin.Inventory.StockCard.index', [
            'title'  => 'Kartu Stok',
            'items'  => $items,
        ]);
    }

    public function report()
    {
        $items = Item::with(['satuan', 'jenis'])
            ->orderBy('nama_barang')
            ->get();

        $pdf = Pdf::loadView('Admin.Inventory.Exports.stock-report', [
            'items'    => $items,
            'settings' => Settings::current(),
            'tanggal'  => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Sisa-Stok-' . now()->format('Y-m-d') . '.pdf');
    }

    public function itemReport(Request $request, Item $item)
    {
        $item->load(['satuan', 'jenis']);

        $query = $item->stockCards()->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $stockCards  = $query->get();
        $totalMasuk  = $stockCards->sum('masuk');
        $totalKeluar = $stockCards->sum('keluar');

        $pdf = Pdf::loadView('Admin.Inventory.Exports.stock-item-report', [
            'item'        => $item,
            'stockCards'  => $stockCards,
            'totalMasuk'  => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'tanggalAwal' => $request->tanggal_awal,
            'tanggalAkhir'=> $request->tanggal_akhir,
            'settings'    => Settings::current(),
            'tanggal'     => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'portrait');

        $filename = 'Kartu-Stok-' . str_replace(' ', '-', $item->nama_barang) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }

    public function itemExportExcel(Request $request, Item $item)
    {
        $filename = 'Kartu-Stok-' . str_replace(' ', '-', $item->nama_barang) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new StockCardItemExport($item, $request->date('tanggal_awal'), $request->date('tanggal_akhir')),
            $filename
        );
    }

    public function show(Request $request, Item $item)
    {
        $page = $request->integer('page', 1);
        $perPage = 3;
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $filter = function ($query) use ($item, $tanggalAwal, $tanggalAkhir) {
            $query->where('item_id', $item->id);

            if ($tanggalAwal) {
                $query->whereDate('tanggal', '>=', $tanggalAwal);
            }

            if ($tanggalAkhir) {
                $query->whereDate('tanggal', '<=', $tanggalAkhir);
            }

            return $query;
        };

        $stockCards = CachedPaginationService::paginate(
            $filter(StockCard::query())->latest('tanggal'),
            $filter(StockCard::with('item')),
            CacheVersionService::key(StockCardService::CACHE_NAMESPACE, [
                'page' => $page,
                'item_id' => $item->id,
                'awal' => $tanggalAwal?->toDateString() ?? '-',
                'akhir' => $tanggalAkhir?->toDateString() ?? '-',
            ]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        return view('Admin.Inventory.StockCard.show', [
            'title' => 'Kartu Stok - ' . $item->nama_barang,
            'item' => $item,
            'stockCards' => $stockCards,
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);
    }
}
