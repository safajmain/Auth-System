<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GymOwnerRegistrationController;
use App\Http\Controllers\AdminGymController;
use App\Http\Controllers\FavoriteGymController;
use App\Http\Controllers\GymDirectoryController;
use App\Http\Controllers\GymReviewController;
use App\Http\Controllers\OwnerGymController;
use App\Http\Controllers\OwnerGymDetailsController;
use App\Http\Controllers\ReminderController;
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


Route::get('/user/personal', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    abort_unless((int) $user->role_id === 1, 403);

    $preference = $user->preferences()->first();

    return view('optifit.user.personal', compact('preference'));
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

    return redirect()->route('user.activities')
        ->with('success', 'تم حفظ البيانات بنجاح');
})->middleware(['auth', 'verified'])
    ->name('user.personal.store');


Route::get('/user/activities', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    abort_unless((int) $user->role_id === 1, 403);

    $selectedActivities = $user->preferredActivities()
        ->pluck('activity_name')
        ->all();

    return view('optifit.user.activities', compact('selectedActivities'));
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

        $records = array_map(fn($id) => [
            'user_id' => $userId,
            'activity_id' => $id,
        ], $activityIds);

        \Illuminate\Support\Facades\DB::table('user_preferred_activities')
            ->insert($records);
    });

    return redirect()->route('user.facilities')
        ->with('success', 'تم حفظ الأنشطة بنجاح');
})->middleware(['auth', 'verified'])
    ->name('user.activities.store');

Route::get('/user/facilities', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    abort_unless((int) $user->role_id === 1, 403);

    $selectedFacilities = $user->preferredFacilities()
        ->pluck('facility_name')
        ->all();

    return view('optifit.user.facilities', compact('selectedFacilities'));
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

        $records = array_map(fn($id) => [
            'user_id' => $userId,
            'facility_id' => $id,
        ], $facilityIds);

        \Illuminate\Support\Facades\DB::table('user_preferred_facilities')
            ->insert($records);
    });

    return redirect()->route('user.times')
        ->with('success', 'تم حفظ المرافق بنجاح');
})->middleware(['auth', 'verified'])
    ->name('user.facilities.store');

// عرض صفحة الأوقات المتاحة

Route::get('/user/times', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    abort_unless((int) $user->role_id === 1, 403);

    $times = \Illuminate\Support\Facades\DB::table('user_available_times')
        ->where('user_id', $user->id)
        ->orderBy('available_time_id')
        ->get();

    return view('optifit.user.times', compact('times'));
})->middleware(['auth', 'verified'])->name('user.times');

// حفظ فترة تدريب جديدة
Route::post('/user/times', function (\Illuminate\Http\Request $request) {
    abort_unless((int) $request->user()->role_id === 1, 403);

    $data = $request->validate([
        'day' => [
            'required',
            \Illuminate\Validation\Rule::in([
                'الأحد',
                'الاثنين',
                'الثلاثاء',
                'الأربعاء',
                'الخميس',
                'الجمعة',
                'السبت',
            ]),
        ],
        'start' => 'required|date_format:H:i',
        'end' => 'required|date_format:H:i|after:start',
    ]);

    \Illuminate\Support\Facades\DB::table('user_available_times')->insert([
        'user_id' => $request->user()->id,
        'day_of_week' => $data['day'],
        'start_time' => $data['start'],
        'end_time' => $data['end'],
    ]);

    return redirect()->route('user.times')
        ->with('success', 'تم حفظ الفترة بنجاح');
})->middleware(['auth', 'verified'])->name('user.times.store');

// حذف فترة تدريب خاصة بالمستخدم
Route::delete('/user/times/{id}', function (
    \Illuminate\Http\Request $request,
    $id
) {
    abort_unless((int) $request->user()->role_id === 1, 403);

    \Illuminate\Support\Facades\DB::table('user_available_times')
        ->where('available_time_id', $id)
        ->where('user_id', $request->user()->id)
        ->delete();

    return redirect()->route('user.times')
        ->with('success', 'تم حذف الفترة بنجاح');
})->middleware(['auth', 'verified'])->name('user.times.destroy');

// Owner routes
Route::middleware(['auth', 'verified', 'role:2'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::resource('gyms', OwnerGymController::class);

        Route::put('gyms/{gym}/facilities', [OwnerGymDetailsController::class, 'facilities'])
            ->name('gyms.facilities.update');

        Route::put('gyms/{gym}/activities', [OwnerGymDetailsController::class, 'activities'])
            ->name('gyms.activities.update');

        Route::post('gyms/{gym}/memberships', [OwnerGymDetailsController::class, 'storeMembership'])
            ->name('gyms.memberships.store');

        Route::delete('gyms/{gym}/memberships/{membership}', [OwnerGymDetailsController::class, 'destroyMembership'])
            ->name('gyms.memberships.destroy');

        Route::post('gyms/{gym}/hours', [OwnerGymDetailsController::class, 'storeHour'])
            ->name('gyms.hours.store');

        Route::delete('gyms/{gym}/hours/{hour}', [OwnerGymDetailsController::class, 'destroyHour'])
            ->name('gyms.hours.destroy');

        Route::post('gyms/{gym}/images', [OwnerGymDetailsController::class, 'storeImage'])
            ->name('gyms.images.store');

        Route::delete('gyms/{gym}/images/{image}', [OwnerGymDetailsController::class, 'destroyImage'])
            ->name('gyms.images.destroy');
    });

// User routes
Route::middleware(['auth', 'verified', 'role:1'])->group(function () {
    Route::get('/gyms', [GymDirectoryController::class, 'index'])->name('gyms.index');
    Route::get('/gyms/compare', [GymDirectoryController::class, 'compare'])->name('gyms.compare');
    Route::get('/gyms/map', [GymDirectoryController::class, 'map'])->name('gyms.map');
    Route::get('/assistant', [GymDirectoryController::class, 'assistant'])->name('assistant.index');
    Route::get('/gyms/{gym}', [GymDirectoryController::class, 'show'])->name('gyms.show');

    Route::get('/favorites', [FavoriteGymController::class, 'index'])->name('favorites.index');
    Route::post('/gyms/{gym}/favorite', [FavoriteGymController::class, 'store'])->name('favorites.store');
    Route::delete('/gyms/{gym}/favorite', [FavoriteGymController::class, 'destroy'])->name('favorites.destroy');

    Route::post('/gyms/{gym}/reviews', [GymReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/gyms/{gym}/reviews/{review}', [GymReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::patch('/reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('reminders.toggle');
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:3'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/gyms', [AdminGymController::class, 'index'])
            ->name('gyms.index');

        Route::get('/gyms/{gym}', [AdminGymController::class, 'show'])
            ->name('gyms.show');

        Route::patch('/gyms/{gym}/status', [AdminGymController::class, 'updateStatus'])
            ->name('gyms.status');

        // عرض وثيقة رسمية - للأدمن فقط
        Route::get('/gyms/{gym}/documents/{document}', [AdminGymController::class, 'showDocument'])
            ->name('gyms.documents.show');

        // مراجعة وثيقة رسمية - للأدمن فقط
        Route::patch('/gyms/{gym}/documents/{document}/status', [AdminGymController::class, 'updateDocumentStatus'])
            ->name('gyms.documents.status');
    });

require __DIR__ . '/auth.php';
require __DIR__ . '/optifit_apex_preview.php';
