<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\StockInExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Settings;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\UnitConversion;
use App\Services\CachedPaginationService;
use App\Services\CacheVersionService;
use App\Services\Inventory\StockCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class StockInController extends Controller
{
    public const CACHE_NAMESPACE = 'stock_ins.index';

    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = 10;
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $filter = function ($query) use ($tanggalAwal, $tanggalAkhir) {
            if ($tanggalAwal) {
                $query->whereDate('tanggal', '>=', $tanggalAwal);
            }

            if ($tanggalAkhir) {
                $query->whereDate('tanggal', '<=', $tanggalAkhir);
            }

            return $query;
        };

        $stockIns = CachedPaginationService::paginate(
            $filter(StockIn::query())->latest('tanggal'),
            $filter(StockIn::with(['supplier', 'items.item', 'items.unit'])),
            CacheVersionService::key(self::CACHE_NAMESPACE, [
                'page' => $page,
                'awal' => $tanggalAwal?->toDateString() ?? '-',
                'akhir' => $tanggalAkhir?->toDateString() ?? '-',
            ]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        return view('Admin.Inventory.StockIn.index', [
            'title' => 'Barang Masuk',
            'stockIns' => $stockIns,
            'suppliers' => Supplier::where('status', 'aktif')->orderBy('nama_supplier')->get(),
            'items' => Item::with('satuan')->where('status', 'aktif')->orderBy('nama_barang')->get(),
            'units' => Unit::where('status', 'aktif')->orderBy('nama_satuan')->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new StockInExport($request->date('tanggal_awal'), $request->date('tanggal_akhir')),
            'barang-masuk-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $query = StockIn::with(['supplier', 'items.item', 'items.unit'])->latest('tanggal');

        if ($tanggalAwal) {
            $query->whereDate('tanggal', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $tanggalAkhir);
        }

        $pdf = Pdf::loadView('Admin.Inventory.Exports.stock-in', [
            'stockIns' => $query->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);

        return $pdf->stream('barang-masuk-' . now()->format('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.qty_input' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($request) {
            $stockIn = StockIn::create([
                'no_transaksi' => $this->generateNoTransaksi(),
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
            ]);

            foreach ($request->items as $row) {
                $item = Item::findOrFail($row['item_id']);
                $qtyBase = $this->convertToBaseUnit($item, $row['unit_id'], $row['qty_input']);

                $stockIn->items()->create([
                    'item_id' => $item->id,
                    'unit_id' => $row['unit_id'],
                    'qty_input' => $row['qty_input'],
                    'qty_base' => $qtyBase,
                ]);

                StockCardService::masuk($item, $qtyBase, $stockIn->no_transaksi);
            }
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Barang masuk berhasil disimpan');

        return back();
    }

    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {
            foreach ($stockIn->items()->with('item')->get() as $stockInItem) {
                StockCardService::keluar($stockInItem->item, $stockInItem->qty_base, 'VOID-' . $stockIn->no_transaksi);
            }

            $stockIn->items()->delete();
            $stockIn->delete();
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Barang masuk berhasil dihapus dan stok dikembalikan');

        return back();
    }

    private function convertToBaseUnit(Item $item, int $unitId, float $qtyInput): float
    {
        if ($unitId === $item->satuan_id) {
            return $qtyInput;
        }

        $conversion = UnitConversion::where('from_unit_id', $unitId)
            ->where('to_unit_id', $item->satuan_id)
            ->first();

        abort_unless($conversion, 422, 'Konversi satuan untuk barang ini belum diatur');

        return $qtyInput * $conversion->factor;
    }

    private function generateNoTransaksi(): string
    {
        $date = now()->format('Ymd');
        $count = StockIn::whereDate('tanggal', now()->toDateString())->count() + 1;
        $prefix = Settings::current()->kode_barang_masuk;

        return sprintf('%s-%s-%03d', $prefix, $date, $count);
    }
}
