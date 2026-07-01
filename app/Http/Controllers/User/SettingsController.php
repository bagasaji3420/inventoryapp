<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;


class SettingsController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $title = 'Setting';

        return view('Admin.User.setting', compact('user', 'title'));
    }

    

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'Username' => [
                'required',
                'string',
                'min:6',
                'max:30',
                'regex:/^[a-zA-Z0-9 ]+$/',
                'unique:users,username,' . $user->id
            ],

            'FirstName' => ['required', 'string', 'max:100'],
            'LastName'  => ['nullable', 'string', 'max:100'],
            'Mobile'    => ['nullable', 'digits_between:10,15'],
            'Pincode'   => ['nullable', 'string', 'max:10'],
            'Address'   => ['nullable', 'string', 'max:255'],
            'City'      => ['nullable', 'string', 'max:100'],
            'State'     => ['nullable', 'string', 'max:100'],
            'BirthDate' => ['nullable', 'date'],
            'Gender'    => ['nullable', 'in:male,female'],

            'avatar' => ['nullable', 'image', 'max:2048'],
        ], [
            'avatar.max' => 'Image size max 2MB',
        ]);

        // 🔥 Upload avatar (tetap di users)
        if ($request->hasFile('avatar')) {

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = upload_image_webp(
                $request->file('avatar'),
                'avatars'
            );
        }

        // ✅ UPDATE USER (core)
        $user->update([
            'username'   => str_replace(' ', '', strtolower($request->Username)),
            'first_name' => $request->FirstName,
            'last_name'  => $request->LastName,
            'avatar'     => $user->avatar,
        ]);

        // ✅ UPDATE PROFILE
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'mobile'  => $request->Mobile,
                'address' => $request->Address,
                'city'    => $request->City,
                'country' => $request->State,
                'birth_date' => $request->BirthDate,
                'gender'     => $request->Gender,
                'pincode'     => $request->Pincode,
            ]
        );

        Alert::success('Success', 'Profile updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Hapus avatar kalau ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout dulu
        Auth::logout();

        // Hapus user (AUTO cascade)
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('', 'Account deleted successfully!');

        return redirect('/')->with('success', 'Account deleted successfully!');
    }
}
