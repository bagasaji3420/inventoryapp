<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create([
            'name' => 'Owner',
            'icon' => '👑',
            'is_protected' => true,
            'is_assignable' => false,
            'is_editable' => false,
        ]);

        Role::create([
            'name' => 'Developer',
            'icon' => '👨‍💻',
            'is_protected' => true,
            'is_assignable' => true,
            'is_editable' => false,
        ]);


        Role::create([
            'name' => 'Admin',
            'icon' => '🛡️', // superuser / full control
            'is_protected' => true,
            'is_assignable' => true,
            'is_editable' => false,
        ]);



        $modules = [
            'dashboard',
            'finance',
            'settings',
            'users',
            'roles',
            'permissions',
            'logs',
            'suppliers',
            'customers',
            'item-types',
            'units',
            'items',
            'stock-ins',
            'stock-outs',
            'delivery-notes',
            'stock-opnames',
            'stock-cards',
            'sales',
        ];

        $actions = [
            'read',
            'create',
            'update',
            'delete'
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $module . '.' . $action,
                    'guard_name' => 'web'
                ]);
            }
        }




        // ambil role
        $owner = Role::where('name', 'Owner')->first();
        $developer = Role::where('name', 'Developer')->first();

        // ambil semua permission
        $allPermissions = Permission::all();

        // ========================
        // 🔥 DEVELOPER = ALL
        // ========================
        $developer->syncPermissions($allPermissions);

        // ========================
        // 👑 OWNER = ALL (full access)
        // ========================
        $owner->syncPermissions($allPermissions);

        $user = User::updateOrCreate(
            ['email' => 'bagas.aji3420@gmail.com'],
            [
                'username' => 'Baito3420',
                'first_name' => 'Bagas',
                'last_name' => 'Aji',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        $user->assignRole('Developer');
    }
}
