<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['satuan', 'jenis'])->orderBy('nama_barang')->get();
        $units = Unit::where('status', 'aktif')->orderBy('nama_satuan')->get();
        $itemTypes = ItemType::where('status', 'aktif')->orderBy('nama_jenis')->get();

        return view('Admin.Inventory.Item.index', [
            'title' => 'Data Barang',
            'items' => $items,
            'units' => $units,
            'itemTypes' => $itemTypes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => [
                'required', 'string', 'max:50',
                Rule::unique('items', 'kode_barang')->whereNull('deleted_at'),
            ],
            'barcode' => [
                'required', 'string', 'max:50',
                Rule::unique('items', 'barcode')->whereNull('deleted_at'),
            ],
            'nama_barang' => 'required|string|max:150',
            'satuan_id' => 'required|exists:units,id',
            'jenis_id' => 'required|exists:item_types,id',
            'harga' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'kode_barang',
            'barcode',
            'nama_barang',
            'satuan_id',
            'jenis_id',
            'harga',
            'stok_minimum',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        Item::create($data);

        Alert::success('Berhasil', 'Barang berhasil ditambahkan');

        return back();
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'kode_barang' => [
                'required', 'string', 'max:50',
                Rule::unique('items', 'kode_barang')->whereNull('deleted_at')->ignore($item->id),
            ],
            'barcode' => [
                'required', 'string', 'max:50',
                Rule::unique('items', 'barcode')->whereNull('deleted_at')->ignore($item->id),
            ],
            'nama_barang' => 'required|string|max:150',
            'satuan_id' => 'required|exists:units,id',
            'jenis_id' => 'required|exists:item_types,id',
            'harga' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'kode_barang',
            'barcode',
            'nama_barang',
            'satuan_id',
            'jenis_id',
            'harga',
            'stok_minimum',
        ]);

        if ($request->hasFile('foto')) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($data);

        Alert::success('Berhasil', 'Barang berhasil diperbarui');

        return back();
    }

    public function destroy(Item $item)
    {
        $item->delete();

        Alert::success('Berhasil', 'Barang berhasil dihapus');

        return back();
    }

    public function toggleStatus(Item $item)
    {
        $item->update([
            'status' => $item->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Alert::success('Berhasil', 'Status barang berhasil diubah');

        return back();
    }

    public function printBarcode(Request $request, Item $item)
    {
        $jumlah = max((int) $request->integer('jumlah', 1), 1);

        return view('Admin.Inventory.Item.barcode-print', [
            'item' => $item,
            'jumlah' => min($jumlah, 100),
            'tampilkanHarga' => $request->boolean('tampilkan_harga', true),
        ]);
    }
}
