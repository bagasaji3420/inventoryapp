<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\StockOutExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Settings;
use App\Models\StockOut;
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

class StockOutController extends Controller
{
    public const CACHE_NAMESPACE = 'stock_outs.index';

    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = 5;
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

        $stockOuts = CachedPaginationService::paginate(
            $filter(StockOut::query())->latest('tanggal'),
            $filter(StockOut::with(['customer', 'items.item', 'items.unit'])),
            CacheVersionService::key(self::CACHE_NAMESPACE, [
                'page' => $page,
                'awal' => $tanggalAwal?->toDateString() ?? '-',
                'akhir' => $tanggalAkhir?->toDateString() ?? '-',
            ]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        return view('Admin.Inventory.StockOut.index', [
            'title' => 'Barang Keluar',
            'stockOuts' => $stockOuts,
            'customers' => Customer::where('status', 'aktif')->orderBy('nama_pelanggan')->get(),
            'items' => Item::where('status', 'aktif')->orderBy('nama_barang')->get(),
            'units' => Unit::where('status', 'aktif')->orderBy('nama_satuan')->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new StockOutExport($request->date('tanggal_awal'), $request->date('tanggal_akhir')),
            'barang-keluar-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $query = StockOut::with(['customer', 'items.item', 'items.unit'])->latest('tanggal');

        if ($tanggalAwal) {
            $query->whereDate('tanggal', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $tanggalAkhir);
        }

        $pdf = Pdf::loadView('Admin.Inventory.Exports.stock-out', [
            'stockOuts' => $query->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);

        return $pdf->stream('barang-keluar-' . now()->format('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.qty_input' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($request) {
            $stockOut = StockOut::create([
                'no_transaksi' => $this->generateNoTransaksi(),
                'customer_id' => $request->customer_id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $row) {
                $item = Item::findOrFail($row['item_id']);
                $qtyBase = $this->convertToBaseUnit($item, $row['unit_id'], $row['qty_input']);

                abort_if($qtyBase > $item->stok, 422, "Stok {$item->nama_barang} tidak mencukupi");

                $stockOut->items()->create([
                    'item_id' => $item->id,
                    'unit_id' => $row['unit_id'],
                    'qty_input' => $row['qty_input'],
                    'qty_base' => $qtyBase,
                ]);

                StockCardService::keluar($item, $qtyBase, $stockOut->no_transaksi);
            }
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Barang keluar berhasil disimpan');

        return back();
    }

    public function destroy(StockOut $stockOut)
    {
        abort_if($stockOut->sale_id, 422, 'Transaksi ini berasal dari Penjualan, batalkan lewat menu Penjualan');

        DB::transaction(function () use ($stockOut) {
            foreach ($stockOut->items()->with('item')->get() as $stockOutItem) {
                StockCardService::masuk($stockOutItem->item, $stockOutItem->qty_base, 'VOID-' . $stockOut->no_transaksi);
            }

            $stockOut->items()->delete();
            $stockOut->delete();
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Barang keluar berhasil dihapus dan stok dikembalikan');

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
        $count = StockOut::whereDate('tanggal', now()->toDateString())->count() + 1;
        $prefix = Settings::current()->kode_barang_keluar;

        return sprintf('%s-%s-%03d', $prefix, $date, $count);
    }
}
