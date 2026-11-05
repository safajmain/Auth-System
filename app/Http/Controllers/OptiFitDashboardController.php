<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OptiFitDashboardController extends Controller
{
    private const ROLE_NAMES = [1 => 'user', 2 => 'owner', 3 => 'admin'];

    public function index(Request $request): View
    {
        $user = $request->user();
        $role = self::ROLE_NAMES[(int) $user->role_id] ?? null;
        abort_unless($role !== null, 403, 'هذا الحساب لا يملك دورًا معرّفًا.');

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
        ];

        return view('optifit.' . $role . '.dashboard', compact('live'));
    }
}
