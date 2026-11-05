<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class GymOwnerRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.gym-owner-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gym_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Same account-registration flow as the original, with the existing owner role.
        // Transaction prevents creating an owner account without its gym.
        $user = DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // role_id is not fillable in the existing User model.
            $user->role_id = 2;
            $user->save();

            Gym::create([
                'owner_id' => $user->id,
                'gym_name' => $data['gym_name'],
                'city' => $data['city'],
                'area' => $data['area'] ?? null,
                'address' => $data['address'],
                'description' => $data['description'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website_url' => $data['website_url'] ?? null,
                'verification_status' => 'pending',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
