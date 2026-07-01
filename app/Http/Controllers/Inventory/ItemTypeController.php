<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ItemTypeController extends Controller
{
    public function index()
    {
        $itemTypes = ItemType::orderBy('nama_jenis')->get();

        return view('Admin.Inventory.ItemType.index', [
            'title' => 'Jenis Barang',
            'itemTypes' => $itemTypes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => [
                'required', 'string', 'max:100',
                Rule::unique('item_types', 'nama_jenis')->whereNull('deleted_at'),
            ],
        ]);

        ItemType::create($request->only('nama_jenis'));

        Alert::success('Berhasil', 'Jenis barang berhasil ditambahkan');

        return back();
    }

    public function update(Request $request, ItemType $itemType)
    {
        $request->validate([
            'nama_jenis' => [
                'required', 'string', 'max:100',
                Rule::unique('item_types', 'nama_jenis')->whereNull('deleted_at')->ignore($itemType->id),
            ],
        ]);

        $itemType->update($request->only('nama_jenis'));

        Alert::success('Berhasil', 'Jenis barang berhasil diperbarui');

        return back();
    }

    public function destroy(ItemType $itemType)
    {
        $itemType->delete();

        Alert::success('Berhasil', 'Jenis barang berhasil dihapus');

        return back();
    }

    public function toggleStatus(ItemType $itemType)
    {
        $itemType->update([
            'status' => $itemType->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Alert::success('Berhasil', 'Status jenis barang berhasil diubah');

        return back();
    }
}
