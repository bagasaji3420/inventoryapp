<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('nama_satuan')->get();

        return view('Admin.Inventory.Unit.index', [
            'title' => 'Satuan',
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => [
                'required', 'string', 'max:50',
                Rule::unique('units', 'nama_satuan')->whereNull('deleted_at'),
            ],
        ]);

        Unit::create($request->only('nama_satuan'));

        Alert::success('Berhasil', 'Satuan berhasil ditambahkan');

        return back();
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_satuan' => [
                'required', 'string', 'max:50',
                Rule::unique('units', 'nama_satuan')->whereNull('deleted_at')->ignore($unit->id),
            ],
        ]);

        $unit->update($request->only('nama_satuan'));

        Alert::success('Berhasil', 'Satuan berhasil diperbarui');

        return back();
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        Alert::success('Berhasil', 'Satuan berhasil dihapus');

        return back();
    }

    public function toggleStatus(Unit $unit)
    {
        $unit->update([
            'status' => $unit->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Alert::success('Berhasil', 'Status satuan berhasil diubah');

        return back();
    }
}
