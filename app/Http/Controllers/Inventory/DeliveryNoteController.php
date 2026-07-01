<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\DeliveryNoteExport;
use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Models\Settings;
use App\Models\StockOut;
use App\Services\CachedPaginationService;
use App\Services\CacheVersionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DeliveryNoteController extends Controller
{
    public const CACHE_NAMESPACE = 'delivery_notes.index';

    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = 5;

        $deliveryNotes = CachedPaginationService::paginate(
            DeliveryNote::query()->latest('tanggal'),
            DeliveryNote::with(['stockOut.customer', 'stockOut.items.item']),
            CacheVersionService::key(self::CACHE_NAMESPACE, ['page' => $page]),
            $page,
            $perPage,
            now()->addMinutes(10)
        );

        $stockOutsWithoutNote = StockOut::whereDoesntHave('deliveryNote')
            ->with('customer')
            ->get();

        return view('Admin.Inventory.DeliveryNote.index', [
            'title' => 'Surat Jalan',
            'deliveryNotes' => $deliveryNotes,
            'stockOutsWithoutNote' => $stockOutsWithoutNote,
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new DeliveryNoteExport($request->date('tanggal_awal'), $request->date('tanggal_akhir')),
            'surat-jalan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->date('tanggal_awal');
        $tanggalAkhir = $request->date('tanggal_akhir');

        $query = DeliveryNote::with(['stockOut.customer'])->latest('tanggal');

        if ($tanggalAwal) {
            $query->whereDate('tanggal', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $tanggalAkhir);
        }

        $pdf = Pdf::loadView('Admin.Inventory.Exports.delivery-note', [
            'deliveryNotes' => $query->get(),
            'tanggalAwal' => $tanggalAwal?->toDateString(),
            'tanggalAkhir' => $tanggalAkhir?->toDateString(),
        ]);

        return $pdf->stream('surat-jalan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function printPdf(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['stockOut.customer', 'stockOut.items.item', 'stockOut.items.unit']);

        $pdf = Pdf::loadView('Admin.Inventory.Exports.delivery-note-print', [
            'deliveryNote' => $deliveryNote,
            'settings' => Settings::current(),
        ]);

        $filename = str_replace(['/', '\\'], '-', $deliveryNote->no_surat) . '.pdf';

        return $pdf->stream($filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_out_id' => 'required|exists:stock_outs,id|unique:delivery_notes,stock_out_id',
            'tanggal' => 'required|date',
            'alamat_tujuan' => 'nullable|string|max:255',
        ]);

        DeliveryNote::create([
            'no_surat' => DeliveryNote::generateNoSurat(),
            'stock_out_id' => $request->stock_out_id,
            'tanggal' => $request->tanggal,
            'alamat_tujuan' => $request->alamat_tujuan,
            'status' => 'draft',
        ]);

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Surat jalan berhasil dibuat');

        return back();
    }

    public function updateStatus(Request $request, DeliveryNote $deliveryNote)
    {
        $request->validate([
            'status' => 'required|in:draft,terkirim,selesai',
        ]);

        $deliveryNote->update(['status' => $request->status]);

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Status surat jalan berhasil diubah');

        return back();
    }

    public function destroy(DeliveryNote $deliveryNote)
    {
        $deliveryNote->delete();

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        Alert::success('Berhasil', 'Surat jalan berhasil dihapus');

        return back();
    }
}
