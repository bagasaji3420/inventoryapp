<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;


class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {

                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }

                if (empty($user->avatar)) {
                    $avatarUrl = $googleUser->getAvatar();

                    try {
                        $response = Http::timeout(5)->get($avatarUrl);

                        if (
                            $response->successful() &&
                            str_contains($response->header('Content-Type'), 'image')
                        ) {
                            $path = upload_image_webp($response->body(), 'avatars', 80);

                            $user->avatar = $path;
                            $user->email_verified_at = now();
                            $user->google_id  = $googleUser->getId();
                        }
                    } catch (\Exception $e) {

                    }
                }

                $user->save();
                Auth::login($user, true);
            } else {
                Alert::error('Akses Ditolak', 'Email ' . $googleUser->getEmail() . ' tidak terdaftar di sistem.');
                return redirect('/login');
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            // dd($e->getMessage());

            Alert::warning('Login Failed', 'Google authentication failed. Try Again ');

            return redirect('/login');
        }
    }

}
