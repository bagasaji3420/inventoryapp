<?php

namespace App\Services\DataTables;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserDataTableService
{
    public function make()
    {
        $users = User::with('roles')->select('users.*');

        return DataTables::of($users)

            ->addColumn('user', function ($user) {
                return view('Admin.User.Datatable.user', compact('user'))->render();
            })

            ->filterColumn('user', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('username', 'like', "%{$keyword}%")
                        ->orWhereRaw(
                            "CONCAT(first_name,' ',last_name) like ?",
                            ["%{$keyword}%"]
                        );
                });
            })

            ->addColumn(
                'role',
                fn($user) =>
                view('Admin.User.Datatable.role', compact('user'))->render()
            )

            ->filterColumn('role', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn(
                'status',
                fn($user) =>
                view('Admin.User.Datatable.status', compact('user'))->render()
            )

            ->addColumn(
                'last_seen',
                fn($user) =>
                view('Admin.User.Datatable.last_seen', compact('user'))->render()
            )

            ->addColumn(
                'created_at',
                fn($user) =>
                $user->created_at->format('d M Y')
            )

            ->addColumn(
                'action',
                fn($user) =>
                view('Admin.User.Datatable.action', compact('user'))->render()
            )

            ->rawColumns(['user', 'role', 'status', 'last_seen', 'action'])
            ->make(true);
    }
}
