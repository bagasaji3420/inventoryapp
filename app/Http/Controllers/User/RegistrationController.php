<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Jobs\SendVerificationEmailJob;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;


class RegistrationController extends Controller
{
    public function index()
    {

        return view('Admin.register');
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            // Step 1 - Account
            'multiStepsUsername'    => ['required', 'string', 'min:6', 'max:30', 'unique:users,username', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'multiStepsEmail' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'multiStepsPass'        => ['required', Password::min(8)],
            'multiStepsConfirmPass' => ['required', 'same:multiStepsPass'],
            'multiStepsFirstName'   => ['required', 'string', 'max:100'],
            'multiStepsLastName'    => ['nullable', 'string', 'max:100'],
            'multiStepsMobile'      => ['nullable', 'digits_between:10,15'],
            'multiStepsPincode'     => ['nullable', 'string', 'max:10'],
            'multiStepsAddress'     => ['nullable', 'string', 'max:255'],
            'multiStepsCity'        => ['nullable', 'string', 'max:100'],
            'multiStepsState'       => ['nullable', 'string', 'max:100'],
            'multiStepsBirthDate' => ['nullable', 'date'],
            'multiStepsGender'    => ['nullable', 'in:male,female'],
        ], [], [
            'multiStepsUsername' => 'username',
            'multiStepsEmail' => 'email',
            'multiStepsPass' => 'password',
            'multiStepsConfirmPass' => 'password confirmation',

        ]);



        // ✅ CREATE USER (ONLY CORE DATA)
        $user = User::create([
            'email'        => $request->multiStepsEmail,
            'password'     => Hash::make($request->multiStepsPass),
            'username'     => str_replace(' ', '', strtolower($request->multiStepsUsername)),
            'first_name'   => $request->multiStepsFirstName,
            'last_name'    => $request->multiStepsLastName,
        ]);


        // ✅ CREATE PROFILE (NEW)
        $user->profile()->create([
            'mobile'   => $request->multiStepsMobile,
            'address'  => $request->multiStepsAddress,
            'city'     => $request->multiStepsCity,
            'country'  => $request->multiStepsState,
            'birth_date' => $request->multiStepsBirthDate,
            'gender'     => $request->multiStepsGender,
            'pincode'     => $request->multiStepsPincode,
        ]);

        $user->assignRole('guest');

        $token = Str::random(64);

        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(60),
        ]);

        dispatch(new SendVerificationEmailJob($user, $token));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
