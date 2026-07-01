<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions', 'users')->orderBy('created_at', 'asc')->get();

        $title = 'Roles';

        $permissions = Permission::all()->groupBy(function ($perm) {
            return Str::before($perm->name, '.');
        });

        // dd($roles);

        return view('Admin.Roles.roles', compact('roles', 'title', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'icon' => 'nullable|string|max:10',
            'is_assignable' => 'nullable|boolean',
            'is_protected' => 'nullable|boolean',
            'is_editable' => 'nullable|boolean',
        ]);

        $role = Role::create([
            'name' => strtolower($request->name),
            'icon' => $request->icon,
            'is_assignable' => $request->boolean('is_assignable'),
            'is_protected' => $request->boolean('is_protected'),
            'is_editable' => $request->boolean('is_editable'),
        ]);

        Alert::success('Success', 'Roles was create!');

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Role created');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'icon' => 'nullable|string|max:10',
            'is_assignable' => 'nullable|boolean',
            'is_protected' => 'nullable|boolean',
            'is_editable' => 'nullable|boolean',
        ]);


        $role->update([
            'name' => strtolower($request->name),
            'icon' => $request->icon,
            'is_assignable' => $request->boolean('is_assignable'),
            'is_protected' => $request->boolean('is_protected'),
            'is_editable' => $request->boolean('is_editable'),
        ]);

        $role->syncPermissions($request->permissions ?? []);

        Alert::success('Success', 'Roles was update!');

        return back();
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        Alert::success('Success', 'Roles was deleted!');

        return back();
    }
}
