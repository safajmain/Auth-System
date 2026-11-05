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

Route::get('/dashboard', [
    \App\Http\Controllers\OptiFitDashboardController::class,
    'index'
])->middleware(['auth', 'verified'])->name('dashboard');

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

Route::get('/user/personal', function () {

    abort_unless(auth()->user()->role_id == 1, 403);

    return view('optifit.user.personal');

})->middleware(['auth', 'verified'])->name('user.personal');

Route::post('/user/personal', function (\Illuminate\Http\Request $request) {

    abort_unless((int) $request->user()->role_id === 1, 403);

    $data = $request->validate([
        'location' => 'required|string|max:255',
        'gender' => 'required|in:male,female',
        'height' => 'required|numeric|between:50,260',
        'weight' => 'required|numeric|between:15,450',
        'fitnessGoal' => 'required|in:bulk,cut,fitness',
        'maxBudget' => 'required|numeric|min:0',
    ]);

    \App\Models\UserPreference::updateOrCreate(
        ['user_id' => $request->user()->id],
        [
            'location' => $data['location'],
            'gender' => $data['gender'],
            'height_cm' => $data['height'],
            'weight_kg' => $data['weight'],
            'fitness_goal' => $data['fitnessGoal'],
            'max_budget' => $data['maxBudget'],
        ]
    );

    return redirect()->route('user.personal')
        ->with('success', 'تم حفظ البيانات بنجاح');

})->middleware(['auth', 'verified'])
  ->name('user.personal.store');

Route::get('/user/activities', function () {
    abort_unless((int) auth()->user()->role_id === 1, 403);

    return view('optifit.user.activities');
})->middleware(['auth', 'verified'])->name('user.activities');

Route::post('/user/activities', function (\Illuminate\Http\Request $request) {

    abort_unless((int) $request->user()->role_id === 1, 403);

    $data = $request->validate([
        'choices' => ['required', 'array', 'min:1'],
        'choices.*' => [
            'required',
            'string',
            'distinct',
            \Illuminate\Validation\Rule::in([
                'Yoga',
                'Swimming',
                'Boxing',
                'Weightlifting',
                'Pilates',
                'Cardio',
            ]),
        ],
    ]);

    $activityIds = \Illuminate\Support\Facades\DB::table('activities')
        ->whereIn('activity_name', $data['choices'])
        ->pluck('activity_id')
        ->all();

    if (count($activityIds) !== count($data['choices'])) {
        return back()->withErrors([
            'choices' => 'بعض الأنشطة غير موجودة في قاعدة البيانات.',
        ]);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($request, $activityIds) {

        $userId = $request->user()->id;

        \Illuminate\Support\Facades\DB::table('user_preferred_activities')
            ->where('user_id', $userId)
            ->delete();

        $records = array_map(fn ($id) => [
            'user_id' => $userId,
            'activity_id' => $id,
        ], $activityIds);

        \Illuminate\Support\Facades\DB::table('user_preferred_activities')
            ->insert($records);
    });

    return redirect()->route('user.activities')
        ->with('success', 'تم حفظ الأنشطة بنجاح');

})->middleware(['auth', 'verified'])
  ->name('user.activities.store');

Route::get('/user/facilities', function () {
    abort_unless((int) auth()->user()->role_id === 1, 403);

    return view('optifit.user.facilities');
})->middleware(['auth', 'verified'])->name('user.facilities');

Route::post('/user/facilities', function (\Illuminate\Http\Request $request) {

    abort_unless((int) $request->user()->role_id === 1, 403);

    $data = $request->validate([
        'choices' => ['required', 'array', 'min:1'],
        'choices.*' => [
            'required',
            'string',
            'distinct',
            \Illuminate\Validation\Rule::in([
                'Swimming Pool',
                'Sauna',
                'Parking',
                'Showers',
                'Lockers',
                'Free Weights Area',
                'Women-Only Section',
                'Jacuzzi',
            ]),
        ],
    ]);

    $facilityIds = \Illuminate\Support\Facades\DB::table('facilities')
        ->whereIn('facility_name', $data['choices'])
        ->pluck('facility_id')
        ->all();

    if (count($facilityIds) !== count($data['choices'])) {
        return back()->withErrors([
            'choices' => 'بعض المرافق غير موجودة في قاعدة البيانات.',
        ]);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($request, $facilityIds) {

        $userId = $request->user()->id;

        \Illuminate\Support\Facades\DB::table('user_preferred_facilities')
            ->where('user_id', $userId)
            ->delete();

        $records = array_map(fn ($id) => [
            'user_id' => $userId,
            'facility_id' => $id,
        ], $facilityIds);

        \Illuminate\Support\Facades\DB::table('user_preferred_facilities')
            ->insert($records);
    });

    return redirect()->route('user.facilities')
        ->with('success', 'تم حفظ المرافق بنجاح');

})->middleware(['auth', 'verified'])
  ->name('user.facilities.store');

require __DIR__ . '/auth.php';
