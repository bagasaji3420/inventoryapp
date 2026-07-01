<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UnitConversionController extends Controller
{
    public function index()
    {
        $conversions = UnitConversion::with(['fromUnit', 'toUnit'])->get();
        $units = Unit::where('status', 'aktif')->orderBy('nama_satuan')->get();

        return view('Admin.Inventory.UnitConversion.index', [
            'title' => 'Konversi Satuan',
            'conversions' => $conversions,
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_unit_id' => 'required|exists:units,id|different:to_unit_id',
            'to_unit_id' => 'required|exists:units,id',
            'factor' => 'required|numeric|gt:0',
        ]);

        UnitConversion::updateOrCreate(
            [
                'from_unit_id' => $request->from_unit_id,
                'to_unit_id' => $request->to_unit_id,
            ],
            ['factor' => $request->factor]
        );

        Alert::success('Berhasil', 'Konversi satuan berhasil disimpan');

        return back();
    }

    public function update(Request $request, UnitConversion $unitConversion)
    {
        $request->validate([
            'factor' => 'required|numeric|gt:0',
        ]);

        $unitConversion->update(['factor' => $request->factor]);

        Alert::success('Berhasil', 'Konversi satuan berhasil diperbarui');

        return back();
    }

    public function destroy(UnitConversion $unitConversion)
    {
        $unitConversion->delete();

        Alert::success('Berhasil', 'Konversi satuan berhasil dihapus');

        return back();
    }
}
