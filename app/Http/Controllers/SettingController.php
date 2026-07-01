<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function codes()
    {
        return view('Admin.Settings.codes', [
            'title' => 'Pengaturan Kode Transaksi',
            'settings' => Settings::current(),
        ]);
    }

    public function updateCodes(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:100',
            'alamat_perusahaan' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'kode_barang_masuk' => 'required|string|max:20|regex:/^[A-Z]+(-[A-Z]+)*$/',
            'kode_barang_keluar' => 'required|string|max:20|regex:/^[A-Z]+(-[A-Z]+)*$/',
            'kode_surat_jalan' => 'required|string|max:20|regex:/^[A-Z]+(-[A-Z]+)*$/',
            'kode_penjualan' => 'required|string|max:20|regex:/^[A-Z]+(-[A-Z]+)*$/',
            'kode_stok_opname' => 'required|string|max:20|regex:/^[A-Z]+(-[A-Z]+)*$/',
        ], [
            'regex' => 'Kode hanya boleh berisi huruf kapital dan tanda strip.',
        ]);

        $settings = Settings::current();

        $data = $request->only([
            'nama_perusahaan',
            'alamat_perusahaan',
            'kode_barang_masuk',
            'kode_barang_keluar',
            'kode_surat_jalan',
            'kode_penjualan',
            'kode_stok_opname',
        ]);

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $settings->update($data);

        Alert::success('Berhasil', 'Pengaturan berhasil disimpan');

        return back();
    }
}
