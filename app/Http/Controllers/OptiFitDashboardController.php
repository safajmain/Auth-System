<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OptiFitDashboardController extends Controller
{
    private const ROLE_NAMES = [1 => 'user', 2 => 'owner', 3 => 'admin'];

   public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $role = self::ROLE_NAMES[(int) $user->role_id] ?? null;
        abort_unless($role !== null, 403, 'هذا الحساب لا يملك دورًا معرّفًا.');
// Check user onboarding completion
if ($role === 'user') {

    $userId = $user->id;

    // 1. Personal information
    if (!\Illuminate\Support\Facades\DB::table('user_preferences')
        ->where('user_id', $userId)->exists()) {
        return redirect()->route('user.personal');
    }

    // 2. Preferred activities
    if (!\Illuminate\Support\Facades\DB::table('user_preferred_activities')
        ->where('user_id', $userId)->exists()) {
        return redirect()->route('user.activities');
    }

    // 3. Preferred facilities
    if (!\Illuminate\Support\Facades\DB::table('user_preferred_facilities')
        ->where('user_id', $userId)->exists()) {
        return redirect()->route('user.facilities');
    }

    // 4. Available times
    if (!\Illuminate\Support\Facades\DB::table('user_available_times')
        ->where('user_id', $userId)->exists()) {
        return redirect()->route('user.times');
    }
}
        $query = Gym::query()->with(['owner', 'memberships', 'facilities', 'activities', 'workingHours', 'images']);
        if ($role === 'user') {
            $query->where('verification_status', 'approved');
        } elseif ($role === 'owner') {
            $query->where('owner_id', $user->id);
        }
        $gyms = $query->orderByDesc('gym_id')->get();
        $list = $gyms->map(function (Gym $gym) {
            $fields = ['gym_name', 'city', 'area', 'address', 'phone', 'website_url', 'description'];
            $complete = collect($fields)->filter(fn ($field) => filled($gym->{$field}))->count();
            $month = $gym->memberships->firstWhere('duration_months', 1);
            return [
                'id' => $gym->gym_id,
                'name' => $gym->gym_name,
                'owner' => $gym->owner?->name ?? '—',
                'area' => $gym->area ?: $gym->city,
                'date' => optional($gym->created_at)->format('Y-m-d'),
                'complete' => (int) round($complete * 100 / count($fields)),
                'status' => $gym->verification_status,
                'price' => $month ? (float) $month->price_jod : 0,
                'phone' => $gym->phone ?: '',
                'notes' => '',
            ];
        })->values()->all();

        $extras = [];
        foreach ($gyms as $gym) {
            $extras[$gym->gym_id] = [
                'description' => $gym->description ?: '',
                'city' => $gym->city,
                'address' => $gym->address,
                'latitude' => $gym->latitude,
                'longitude' => $gym->longitude,
                'website_url' => $gym->website_url ?: '',
                'facilities' => $gym->facilities->pluck('facility_name')->values()->all(),
                'activities' => $gym->activities->pluck('activity_name')->values()->all(),
                'memberships' => $gym->memberships->map(fn ($m) => [
                    'duration' => $m->duration_months, 'price' => (float) $m->price_jod,
                ])->values()->all(),
                'hours' => $gym->workingHours->map(fn ($h) => [
                    'day' => $h->day_of_week,
                    'open' => $h->open_time,
                    'close' => $h->close_time,
                ])->values()->all(),
                'images' => $gym->images->map(fn ($i) => asset('storage/' . ltrim($i->image_path, '/')))->values()->all(),
            ];
        }

        $users = $role === 'admin'
            ? User::query()->with('role')->orderByDesc('id')->get()->map(fn ($u) => [
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role?->role_name ?? '',
                'active' => 'نشط', // لا توجد حالة تعطيل حساب في الجداول الحالية.
            ])->values()->all()
            : [];

        $live = [
            'role' => $role,
            'view' => 'dashboard',
            'userId' => $user->id,
            'user' => ['name' => $user->name, 'email' => $user->email],
            'gyms' => $list,
            'users' => $users,
            'ownerGymIds' => $role === 'owner' ? $gyms->pluck('gym_id')->values()->all() : [],
            'ownerExtras' => $extras,
            'ownerStats' => [],
            'adminStats' => [],
        ];

        if ($role === 'owner') {
            $ownerGymIds = $gyms->pluck('gym_id');
            $live['ownerStats'] = [
                'reviews' => DB::table('gym_reviews')->whereIn('gym_id', $ownerGymIds)->count(),
                'favorites' => DB::table('user_favorite_gyms')->whereIn('gym_id', $ownerGymIds)->count(),
                'memberships' => DB::table('gym_memberships')->whereIn('gym_id', $ownerGymIds)->count(),
                'average_rating' => round((float) (DB::table('gym_reviews')
                    ->whereIn('gym_id', $ownerGymIds)
                    ->avg('rating') ?? 0), 1),
            ];
        }

        if ($role === 'admin') {
            $live['adminStats'] = [
                'users' => User::query()->count(),
                'reviews' => DB::table('gym_reviews')->count(),
                'favorites' => DB::table('user_favorite_gyms')->count(),
                'audit_logs' => DB::table('audit_logs')->count(),
            ];
        }

        if ($role === 'user') {
            $preference = $user->preferences()->first();
            $preferredActivities = $user->preferredActivities()
                ->orderBy('activity_name')
                ->pluck('activity_name')
                ->values();
            $preferredFacilities = $user->preferredFacilities()
                ->orderBy('facility_name')
                ->pluck('facility_name')
                ->values();
            $availableTimes = $user->availableTimes()
                ->orderBy('available_time_id')
                ->get(['day_of_week', 'start_time', 'end_time']);
            $favoriteIds = $user->favoriteGyms()
                ->pluck('gyms.gym_id')
                ->map(fn ($id) => (int) $id)
                ->values();

            $activityLookup = $preferredActivities->flip();
            $facilityLookup = $preferredFacilities->flip();
            $location = trim((string) ($preference?->location ?? ''));
            $budget = $preference?->max_budget !== null ? (float) $preference->max_budget : null;

            $gymCards = $gyms->map(function (Gym $gym) use (
                $activityLookup,
                $facilityLookup,
                $favoriteIds,
                $location,
                $budget
            ) {
                $activities = $gym->activities->pluck('activity_name')->values();
                $facilities = $gym->facilities->pluck('facility_name')->values();
                $month = $gym->memberships->firstWhere('duration_months', 1);
                $price = $month ? (float) $month->price_jod : null;
                $locationMatched = $location !== '' && collect([
                    $gym->city,
                    $gym->area,
                    $gym->address,
                ])->filter()->contains(fn ($value) => str_contains(
                    mb_strtolower((string) $value),
                    mb_strtolower($location)
                ));
                $matchedActivities = $activities->filter(
                    fn ($name) => $activityLookup->has($name)
                )->values();
                $matchedFacilities = $facilities->filter(
                    fn ($name) => $facilityLookup->has($name)
                )->values();
                $budgetMatched = $budget !== null && $price !== null && $price <= $budget;

                return [
                    'id' => (int) $gym->gym_id,
                    'name' => $gym->gym_name,
                    'description' => $gym->description ?: '',
                    'city' => $gym->city ?: '',
                    'area' => $gym->area ?: '',
                    'address' => $gym->address ?: '',
                    'phone' => $gym->phone ?: '',
                    'website_url' => $gym->website_url ?: '',
                    'price' => $price,
                    'image' => $gym->images->first()
                        ? asset('storage/' . ltrim($gym->images->first()->image_path, '/'))
                        : null,
                    'activities' => $activities->all(),
                    'facilities' => $facilities->all(),
                    'memberships' => $gym->memberships->map(fn ($membership) => [
                        'duration' => (int) $membership->duration_months,
                        'price' => (float) $membership->price_jod,
                    ])->values()->all(),
                    'hours' => $gym->workingHours->map(fn ($hour) => [
                        'day' => $hour->day_of_week,
                        'open' => $hour->open_time,
                        'close' => $hour->close_time,
                    ])->values()->all(),
                    'favorite' => $favoriteIds->contains((int) $gym->gym_id),
                    'matches' => [
                        'activities' => $matchedActivities->all(),
                        'facilities' => $matchedFacilities->all(),
                        'location' => $locationMatched,
                        'budget' => $budgetMatched,
                    ],
                    'match_count' => $matchedActivities->count()
                        + $matchedFacilities->count()
                        + (int) $locationMatched
                        + (int) $budgetMatched,
                ];
            })->sortByDesc('match_count')->values();

            $dashboard = [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'initial' => mb_strtoupper(mb_substr(trim($user->name), 0, 1)),
                ],
                'preference' => [
                    'location' => $preference?->location ?: null,
                    'goal' => $preference?->fitness_goal ?: null,
                    'budget' => $budget,
                ],
                'activities' => $preferredActivities->all(),
                'facilities' => $preferredFacilities->all(),
                'times' => $availableTimes->map(fn ($time) => [
                    'day' => $time->day_of_week,
                    'start' => substr((string) $time->start_time, 0, 5),
                    'end' => substr((string) $time->end_time, 0, 5),
                ])->all(),
                'stats' => [
                    'approved_gyms' => $gyms->count(),
                    'favorites' => $favoriteIds->count(),
                    'times' => $availableTimes->count(),
                    'reviews' => DB::table('gym_reviews')->where('user_id', $user->id)->count(),
                ],
                'gyms' => $gymCards->all(),
            ];

            return view('optifit.user.dashboard', compact('live', 'dashboard'));
        }

        return view('optifit.' . $role . '.dashboard', compact('live'));
    }
}
