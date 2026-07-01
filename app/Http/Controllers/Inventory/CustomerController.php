<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('nama_pelanggan')->get();

        return view('Admin.Inventory.Customer.index', [
            'title' => 'Pelanggan',
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:255',
        ]);

        Customer::create($request->only(['nama_pelanggan', 'email', 'telepon', 'alamat']));

        Alert::success('Berhasil', 'Pelanggan berhasil ditambahkan');

        return back();
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:255',
        ]);

        $customer->update($request->only(['nama_pelanggan', 'email', 'telepon', 'alamat']));

        Alert::success('Berhasil', 'Pelanggan berhasil diperbarui');

        return back();
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        Alert::success('Berhasil', 'Pelanggan berhasil dihapus');

        return back();
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->update([
            'status' => $customer->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Alert::success('Berhasil', 'Status pelanggan berhasil diubah');

        return back();
    }
}
