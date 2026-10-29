<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        if (
            empty($googleUser->getEmail()) ||
            !($googleUser->user['verified_email'] ?? false)
        ) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google account must have a verified email address.',
                ]);
        }
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'This email already has an account. Please log in with your existing method first.',
                ]);
        }
        $user = User::create([
            'name' => $googleUser->getName() ?? 'Google User',
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(40)),
            'google_id' => $googleUser->getId(),
        ]);

        $user->email_verified_at = now();
        $user->role_id = 1;
        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
