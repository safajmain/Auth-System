<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GymOwnerRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
Route::get('/', function () {
    return view('welcome');
});

// Google Login
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('google.callback');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Gym owner registration
Route::middleware('guest')->group(function () {
    Route::get('/gym-owner/register', [GymOwnerRegistrationController::class, 'create'])
        ->name('gym-owner.register');

    Route::post('/gym-owner/register', [GymOwnerRegistrationController::class, 'store'])
        ->name('gym-owner.register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
