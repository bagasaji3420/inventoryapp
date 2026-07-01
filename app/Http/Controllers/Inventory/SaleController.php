<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\SaleExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Settings;
use App\Models\User;
use App\Notifications\LowStockNotification;
use App\Services\CachedPaginationService;
use App\Services\CacheVersionService;
use App\Services\Inventory\StockCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class SaleController extends Controller
{
    public const CACHE_NAMESPACE = 'sales.index';

    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = 2;
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

        $sales = CachedPaginationService::paginate(
            $filter(Sale::query())->latest('tanggal'),
            $filter(Sale::with(['customer', 'items.item', 'payments'])),
            CacheVersionService::key(self::CACHE_NAMESPACE, [
                'page' => $page,
                'awal' => $tanggalAwal?->toDateString() ?? '-',
                'akhir' => $tanggalAkhir?->toDateString() ?? '-',
            ]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        return view('Admin.Inventory.Sale.index', [
            'title' => 'Kasir Penjualan',
            'sales' => $sales,
            'customers' => Customer::where('status', 'aktif')->orderBy('nama_pelanggan')->get(),
            'items' => Item::where('status', 'aktif')->orderBy('nama_barang')->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new SaleExport($request->date('tanggal_awal'), $request->date('tanggal_akhir')),
            'penjualan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $query = Sale::with(['customer'])->latest('tanggal');

        if ($tanggalAwal) {
            $query->whereDate('tanggal', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $tanggalAkhir);
        }

        $pdf = Pdf::loadView('Admin.Inventory.Exports.sale', [
            'sales' => $query->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);

        return $pdf->stream('penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.item', 'payments', 'stockOut.deliveryNote']);

        return view('Admin.Inventory.Sale.show', [
            'title' => 'Detail Penjualan',
            'sale' => $sale,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|gt:0',
            'bayar' => 'required|numeric|min:0',
        ]);

        $sale = DB::transaction(function () use ($request) {
            $customer = Customer::findOrFail($request->customer_id);

            $total = 0;
            $cart = [];

            foreach ($request->items as $row) {
                $item = Item::findOrFail($row['item_id']);

                abort_if($row['qty'] > $item->stok, 422, "Stok {$item->nama_barang} tidak mencukupi");

                $subtotal = $item->harga * $row['qty'];
                $total += $subtotal;

                $cart[] = [
                    'item' => $item,
                    'qty' => $row['qty'],
                    'harga' => $item->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $bayar = $request->bayar;
            $statusPembayaran = $bayar <= 0 ? 'belum_bayar' : ($bayar >= $total ? 'lunas' : 'sebagian');

            $sale = Sale::create([
                'no_faktur' => $this->generateNoFaktur(),
                'customer_id' => $customer->id,
                'tanggal' => $request->tanggal,
                'total' => $total,
                'status_pembayaran' => $statusPembayaran,
            ]);

            foreach ($cart as $row) {
                $sale->items()->create([
                    'item_id' => $row['item']->id,
                    'qty' => $row['qty'],
                    'harga' => $row['harga'],
                    'subtotal' => $row['subtotal'],
                ]);
            }

            if ($bayar > 0) {
                $sale->payments()->create([
                    'tanggal' => $request->tanggal,
                    'jumlah' => $bayar,
                    'keterangan' => 'Pembayaran awal di kasir',
                ]);
            }

            $stockOut = $sale->stockOut()->create([
                'no_transaksi' => $this->generateNoTransaksiKeluar(),
                'customer_id' => $customer->id,
                'tanggal' => $request->tanggal,
                'keterangan' => 'Otomatis dari Kasir Penjualan ' . $sale->no_faktur,
            ]);

            foreach ($cart as $row) {
                $item = $row['item'];

                $stockOut->items()->create([
                    'item_id' => $item->id,
                    'unit_id' => $item->satuan_id,
                    'qty_input' => $row['qty'],
                    'qty_base' => $row['qty'],
                ]);

                StockCardService::keluar($item, $row['qty'], $sale->no_faktur);
            }

            DeliveryNote::create([
                'no_surat' => DeliveryNote::generateNoSurat(),
                'stock_out_id' => $stockOut->id,
                'tanggal' => $request->tanggal,
                'alamat_tujuan' => $customer->alamat,
                'status' => 'draft',
            ]);

            return $sale;
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);
        CacheVersionService::bump(StockOutController::CACHE_NAMESPACE);
        CacheVersionService::bump(DeliveryNoteController::CACHE_NAMESPACE);

        // Notifikasi stok rendah / habis setelah penjualan
        $this->checkAndNotifyLowStock($sale);

        Alert::success('Berhasil', "Penjualan {$sale->no_faktur} berhasil disimpan, kembalian: " . max($request->bayar - $sale->total, 0));

        return back();
    }

    private function checkAndNotifyLowStock(Sale $sale): void
    {
        $recipients = User::permission('items.read')->where('status', 'active')->get();
        if ($recipients->isEmpty()) {
            return;
        }

        // Ambil item dari penjualan ini, reload stok terbaru
        $itemIds = $sale->stockOut?->items()->pluck('item_id') ?? collect();
        $items = Item::with('satuan')->whereIn('id', $itemIds)->get();

        foreach ($items as $item) {
            if ($item->stok <= 30) {
                $recipients->each(fn ($user) => $user->notify(new LowStockNotification($item)));
            }
        }
    }

    public function addPayment(Request $request, Sale $sale)
    {
        abort_if($sale->status_pembayaran === 'lunas', 422, 'Penjualan ini sudah lunas');

        $request->validate([
            'jumlah' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($request, $sale) {
            $sale->payments()->create([
                'tanggal' => now()->toDateString(),
                'jumlah' => $request->jumlah,
                'keterangan' => 'Cicilan pembayaran',
            ]);

            $totalDibayar = $sale->payments()->sum('jumlah');

            $sale->update([
                'status_pembayaran' => $totalDibayar >= $sale->total ? 'lunas' : 'sebagian',
            ]);
        });

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Pembayaran berhasil dicatat');

        return back();
    }

    private function generateNoFaktur(): string
    {
        $date = now()->format('Ymd');
        $count = Sale::whereDate('tanggal', now()->toDateString())->count() + 1;
        $prefix = Settings::current()->kode_penjualan;

        return sprintf('%s-%s%04d', $prefix, $date, $count);
    }

    private function generateNoTransaksiKeluar(): string
    {
        $date = now()->format('Ymd');
        $count = \App\Models\StockOut::whereDate('tanggal', now()->toDateString())->count() + 1;
        $prefix = Settings::current()->kode_barang_keluar;

        return sprintf('%s-%s-%03d', $prefix, $date, $count);
    }
}
