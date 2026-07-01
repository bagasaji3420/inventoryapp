<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Settings;
use App\Models\StockOpname;
use App\Services\CachedPaginationService;
use App\Services\CacheVersionService;
use App\Services\Inventory\StockCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class StockOpnameController extends Controller
{
    public const CACHE_NAMESPACE = 'stock_opnames.index';

    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = 10;
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $filter = function ($query) use ($tanggalAwal, $tanggalAkhir) {
            if ($tanggalAwal) {
                $query->whereDate('tanggal_opname', '>=', $tanggalAwal);
            }

            if ($tanggalAkhir) {
                $query->whereDate('tanggal_opname', '<=', $tanggalAkhir);
            }

            return $query;
        };

        $stockOpnames = CachedPaginationService::paginate(
            $filter(StockOpname::query())->latest('tanggal_opname'),
            $filter(StockOpname::with(['petugas', 'items'])),
            CacheVersionService::key(self::CACHE_NAMESPACE, [
                'page' => $page,
                'awal' => $tanggalAwal?->toDateString() ?? '-',
                'akhir' => $tanggalAkhir?->toDateString() ?? '-',
            ]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        return view('Admin.Inventory.StockOpname.index', [
            'title' => 'Stok Opname',
            'stockOpnames' => $stockOpnames,
            'items' => Item::where('status', 'aktif')->orderBy('nama_barang')->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['petugas', 'items.item']);

        return view('Admin.Inventory.StockOpname.show', [
            'title' => 'Detail Stok Opname',
            'stockOpname' => $stockOpname,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_opname' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id|distinct',
            'items.*.stok_fisik' => 'required|numeric|min:0',
        ], [
            'items.*.item_id.distinct' => 'Satu barang tidak boleh dipilih lebih dari sekali dalam satu opname.',
        ]);

        DB::transaction(function () use ($request) {
            $stockOpname = StockOpname::create([
                'no_opname' => $this->generateNoOpname(),
                'tanggal_opname' => $request->tanggal_opname,
                'petugas_id' => Auth::id(),
                'status' => 'selesai',
            ]);

            foreach ($request->items as $row) {
                $item = Item::findOrFail($row['item_id']);
                $stokSistem = $item->stok;
                $stokFisik = $row['stok_fisik'];
                $selisih = $stokFisik - $stokSistem;

                $stockOpname->items()->create([
                    'item_id' => $item->id,
                    'stok_sistem' => $stokSistem,
                    'stok_fisik' => $stokFisik,
                    'selisih' => $selisih,
                ]);

                if ($selisih != 0) {
                    StockCardService::opname($item, $selisih, $stockOpname->no_opname);
                }
            }
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Stok opname berhasil disimpan');

        return back();
    }

    private function generateNoOpname(): string
    {
        $date = now()->format('Ymd');
        $count = StockOpname::whereDate('tanggal_opname', now()->toDateString())->count() + 1;
        $prefix = Settings::current()->kode_stok_opname;

        return sprintf('%s%s%04d', $prefix, $date, $count);
    }
}
