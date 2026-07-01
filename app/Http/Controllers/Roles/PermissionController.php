<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->get();

        $title = 'Permission';

        return view('Admin.Roles.permission', compact('permissions', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $base = strtolower($request->name);

        $permissions = [
            $base . '.read',
            $base . '.create',
            $base . '.update',
            $base . '.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        return back()->with('success', 'Permissions created');
    }


    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // ambil prefix lama (user)
        $oldBase = Str::before($permission->name, '.');

        // prefix baru (admin)
        $newBase = strtolower(str_replace(' ', '_', $request->name));

        // ambil semua permission dalam group lama
        $permissions = Permission::where('name', 'like', $oldBase . '.%')->get();

        foreach ($permissions as $perm) {
            // ambil action (read, create, dst)
            $action = Str::after($perm->name, '.');

            $newName = $newBase . '.' . $action;

            // hindari duplicate
            if (!Permission::where('name', $newName)->exists()) {
                $perm->update(['name' => $newName]);
            }
        }

        return back()->with('success', 'Permissions updated');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // ambil prefix sebelum titik
        $base = explode('.', $permission->name)[0];

        // hapus semua permission dengan prefix sama
        Permission::where('name', 'like', $base . '.%')->delete();

        return back()->with('success', 'All related permissions deleted');
    }
}
