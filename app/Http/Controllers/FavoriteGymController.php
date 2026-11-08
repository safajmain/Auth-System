<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteGymController extends Controller
{
    public function index(Request $request): View
    {
        $gyms = $request->user()->favoriteGyms()
            ->where('verification_status', 'approved')
            ->with(['activities', 'facilities', 'memberships', 'images'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest('user_favorite_gyms.created_at')
            ->get();

        return view('optifit.user.gyms.favorites', compact('gyms'));
    }

    public function store(Request $request, Gym $gym): RedirectResponse
    {
        abort_unless($gym->verification_status === 'approved', 404);
        $request->user()->favoriteGyms()->syncWithoutDetaching([
            $gym->gym_id => ['created_at' => now()],
        ]);

        return back()->with('success', 'تمت إضافة النادي إلى المفضلة.');
    }

    public function destroy(Request $request, Gym $gym): RedirectResponse
    {
        $request->user()->favoriteGyms()->detach($gym->gym_id);

        return back()->with('success', 'تمت إزالة النادي من المفضلة.');
    }
}
