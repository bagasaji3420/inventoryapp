<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationEmailJob;
use App\Models\EmailVerification;
use App\Notifications\UserNotification;
use App\Services\DataTables\UserDataTableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        $activeUsers = \App\Models\User::where('status', 'active')->count();
        $suspendUsers = \App\Models\User::where('status', 'suspend')->count();
        $bannedUsers = \App\Models\User::where('status', 'banned')->count();

        $title = "User";

        return view('Admin.User.index', compact('title', 'activeUsers', 'suspendUsers', 'bannedUsers'));
    }

    public function show($id)
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'first_name' => 'required|max:20|min:2',
            'last_name'  => 'required|max:20|min:3',
            'username'   => 'required|max:20|min:5',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:5',
            'roles'      => 'required|array'
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name may not be greater than 20 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 3 characters.',
            'last_name.max' => 'Last name may not be greater than 20 characters.',

            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 5 characters.',
            'username.max' => 'Username may not be greater than 20 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 5 characters.',

            'roles.required' => 'Please select at least one role.',
        ]);

        $user = User::create([
            'email' => $request->email,
            'username'     => str_replace(' ', '', strtolower($request->username)),
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'password' => Hash::make($request->password),
            'status' => 'active'
        ]);

        $token = Str::random(64);

        // simpan dulu
        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(60),
        ]);

        // kirim email verification
        dispatch(new SendVerificationEmailJob($user, $token));

        $user->assignRole($request->roles);

        return back()->with('success', 'User created');
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // ✅ VALIDATION
        $request->validate([
            'status' => 'required|in:active,suspend,banned',
            'status_reason' => 'required_if:status,suspend|required_if:status,banned',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'suspended_until' => 'required_if:status,suspend|nullable|date'
        ]);



        $suspend_until = \Carbon\Carbon::parse($request->suspended_until);

        // ✅ UPDATE STATUS
        $user->update([
            'status' => $request->status,
            'status_reason' => $request->status_reason,
            'suspended_until' => $request->status === 'suspend'
                ? $suspend_until
                : null
        ]);


        $message = match ($request->status) {
            'active' => 'Your account has been reactivated.',
            'suspend' => 'Your account has been temporarily suspended.',
            'banned' => 'Your account has been permanently banned.',
            default => 'Your account status has been updated.',
        };


        // 🔥 KIRIM NOTIF KE USER (PENERIMA = $user)
        $actor = Auth::user(); // admin / yang ngubah

        if ($request->status != 'active') {
            $user->notify(new UserNotification([
                'title' => $message,
                'message' => $request->status_reason ?? 'Active',
                'type' => 'system',
                'icon' => match ($request->status) {
                    'active' => 'bx-check-circle',
                    'suspend' => 'bx-time',
                    'banned' => 'bx-block',
                    default => 'bx-bell'
                },
                'color' => match ($request->status) {
                    'active' => 'success',
                    'suspend' => 'warning',
                    'banned' => 'danger',
                    default => 'primary'
                },

                // optional tapi penting 🔥
                'sender_id' => $actor->id,
                'sender_name' => $actor->name,
            ]));
        } else {
            $oldRoles = $user->getRoleNames()->toArray();
            $newRoles = $request->roles;

            $addedRoles = array_diff($newRoles, $oldRoles);
            $removedRoles = array_diff($oldRoles, $newRoles);

            $user->syncRoles($newRoles);

            $actor = Auth::user();

            foreach ($addedRoles as $role) {
                $user->notify(new UserNotification([
                    'title' => 'Role Assigned',
                    'message' => "You have been assigned the role: " . ucfirst($role),
                    'type' => 'system',
                    'icon' => 'bx-user-plus',
                    'color' => 'success',

                    'sender_id' => $actor->id,
                    'sender_name' => $actor->name,
                ]));
            }

            foreach ($removedRoles as $role) {
                $user->notify(new UserNotification([
                    'title' => 'Role Removed',
                    'message' => "Role " . ucfirst($role) . " has been removed from your account",
                    'type' => 'system',
                    'icon' => 'bx-user-x',
                    'color' => 'danger',

                    'sender_id' => $actor->id,
                    'sender_name' => $actor->name,
                ]));
            }
        }



        return back()->with('success', 'User updated');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->roles()->where('is_protected', true)->exists()) {
            return back()->with('error', 'This user cant be deleted');
        }

        $user->delete();

        return back()->with('success', 'User deleted');
    }


    public function data(UserDataTableService $dataTable)
    {
        return $dataTable->make();
    }
}
