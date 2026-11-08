<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Facility;
use App\Models\Gym;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GymDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:100'],
            'activity' => ['nullable', 'integer', 'exists:activities,activity_id'],
            'facility' => ['nullable', 'integer', 'exists:facilities,facility_id'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', Rule::in(['newest', 'name', 'rating', 'price'])],
        ]);

        $query = $this->approvedQuery();
        $query->when($filters['q'] ?? null, function (Builder $builder, string $term): void {
            $builder->where(function (Builder $search) use ($term): void {
                $search->where('gym_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%");
            });
        })->when($filters['city'] ?? null, function (Builder $builder, string $city): void {
            $builder->where(function (Builder $location) use ($city): void {
                $location->where('city', 'like', "%{$city}%")
                    ->orWhere('area', 'like', "%{$city}%");
            });
        })->when($filters['activity'] ?? null, fn (Builder $builder, int $id) =>
            $builder->whereHas('activities', fn (Builder $relation) => $relation->where('activities.activity_id', $id))
        )->when($filters['facility'] ?? null, fn (Builder $builder, int $id) =>
            $builder->whereHas('facilities', fn (Builder $relation) => $relation->where('facilities.facility_id', $id))
        )->when($filters['max_price'] ?? null, fn (Builder $builder, $price) =>
            $builder->whereHas('memberships', fn (Builder $relation) => $relation->where('price_jod', '<=', $price))
        );

        match ($filters['sort'] ?? 'newest') {
            'name' => $query->orderBy('gym_name'),
            'rating' => $query->orderByDesc('reviews_avg_rating'),
            'price' => $query->orderBy('memberships_min_price_jod'),
            default => $query->latest('gym_id'),
        };

        $gyms = $query->paginate(12)->withQueryString();
        $activities = Activity::query()->orderBy('activity_name')->get();
        $facilities = Facility::query()->orderBy('facility_name')->get();
        $favoriteIds = $request->user()->favoriteGyms()->pluck('gyms.gym_id')->map(fn ($id) => (int) $id);

        return view('optifit.user.gyms.index', compact(
            'gyms', 'activities', 'facilities', 'filters', 'favoriteIds'
        ));
    }

    public function show(Request $request, Gym $gym): View
    {
        $this->authorize('view', $gym);
        $gym->load([
            'owner', 'activities', 'facilities', 'memberships',
            'workingHours', 'images', 'reviews.user',
        ])->loadCount('favoritedByUsers')->loadAvg('reviews', 'rating');
        $isFavorite = $request->user()->favoriteGyms()
            ->where('gyms.gym_id', $gym->gym_id)
            ->exists();
        $myReview = $gym->reviews()->where('user_id', $request->user()->id)->first();

        return view('optifit.user.gyms.show', compact('gym', 'isFavorite', 'myReview'));
    }

    public function compare(Request $request): View
    {
        $data = $request->validate([
            'gyms' => ['nullable', 'array', 'max:3'],
            'gyms.*' => ['integer', 'distinct', 'exists:gyms,gym_id'],
        ]);
        $ids = collect($data['gyms'] ?? [])->map(fn ($id) => (int) $id)->all();
        $gyms = $ids
            ? $this->approvedQuery()->whereIn('gym_id', $ids)->get()->sortBy(
                fn (Gym $gym) => array_search((int) $gym->gym_id, $ids, true)
            )->values()
            : collect();
        $availableGyms = Gym::query()->where('verification_status', 'approved')
            ->orderBy('gym_name')->get(['gym_id', 'gym_name']);

        return view('optifit.user.gyms.compare', compact('gyms', 'availableGyms', 'ids'));
    }

    public function map(): View
    {
        $gyms = $this->approvedQuery()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('latitude', [-90, 90])
            ->whereBetween('longitude', [-180, 180])
            ->get();

        $mapGyms = $gyms->map(fn (Gym $gym): array => [
            'name' => $gym->gym_name,
            'lat' => (float) $gym->latitude,
            'lng' => (float) $gym->longitude,
            'url' => route('gyms.show', $gym),
            'city' => $gym->city,
            'area' => $gym->area,
        ])->values();

        return view('optifit.user.gyms.map', compact('gyms', 'mapGyms'));
    }

    public function assistant(Request $request): View
    {
        $user = $request->user();
        $preference = $user->preferences()->first();
        $activityNames = $user->preferredActivities()->pluck('activity_name');
        $facilityNames = $user->preferredFacilities()->pluck('facility_name');
        $budget = $preference?->max_budget !== null ? (float) $preference->max_budget : null;
        $location = mb_strtolower(trim((string) $preference?->location));

        $recommendations = $this->approvedQuery()->get()->map(function (Gym $gym) use (
            $activityNames, $facilityNames, $budget, $location
        ): Gym {
            $activityMatches = $gym->activities->pluck('activity_name')->intersect($activityNames)->count();
            $facilityMatches = $gym->facilities->pluck('facility_name')->intersect($facilityNames)->count();
            $price = $gym->memberships->min('price_jod');
            $locationMatch = $location !== '' && collect([$gym->city, $gym->area, $gym->address])
                ->filter()->contains(fn ($value) => str_contains(mb_strtolower((string) $value), $location));
            $budgetMatch = $budget !== null && $price !== null && (float) $price <= $budget;
            $gym->setAttribute('match_score', $activityMatches * 3 + $facilityMatches * 2 + (int) $locationMatch * 2 + (int) $budgetMatch);

            return $gym;
        })->sortByDesc('match_score')->values();

        return view('optifit.user.gyms.assistant', compact(
            'recommendations', 'preference', 'activityNames', 'facilityNames'
        ));
    }

    private function approvedQuery(): Builder
    {
        return Gym::query()
            ->where('verification_status', 'approved')
            ->with(['owner', 'activities', 'facilities', 'memberships', 'workingHours', 'images'])
            ->withCount(['reviews', 'favoritedByUsers'])
            ->withAvg('reviews', 'rating')
            ->withMin('memberships', 'price_jod');
    }
}
