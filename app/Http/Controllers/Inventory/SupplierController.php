<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('Admin.Inventory.Supplier.index', [
            'title' => 'Supplier',
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'kontak_person' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:255',
        ]);

        Supplier::create($request->only(['nama_supplier', 'kontak_person', 'telepon', 'alamat']));

        Alert::success('Berhasil', 'Supplier berhasil ditambahkan');

        return back();
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'kontak_person' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:255',
        ]);

        $supplier->update($request->only(['nama_supplier', 'kontak_person', 'telepon', 'alamat']));

        Alert::success('Berhasil', 'Supplier berhasil diperbarui');

        return back();
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        Alert::success('Berhasil', 'Supplier berhasil dihapus');

        return back();
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update([
            'status' => $supplier->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Alert::success('Berhasil', 'Status supplier berhasil diubah');

        return back();
    }
}
