<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteGymRequest;
use App\Http\Requests\StoreGymRequest;
use App\Http\Requests\UpdateGymRequest;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\Facility;
use App\Models\Gym;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OwnerGymController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Gym::class);
        abort_unless((int) auth()->user()->role_id === 2, 403);

        $gyms = auth()->user()->gyms()
            ->withCount(['reviews', 'favoritedByUsers'])
            ->withAvg('reviews', 'rating')
            ->with(['images', 'memberships'])
            ->latest('gym_id')
            ->get();

        return view('optifit.owner.gyms.index', compact('gyms'));
    }

    public function create(): View
    {
        $this->authorize('create', Gym::class);

        return view('optifit.owner.gyms.form', ['gym' => new Gym()]);
    }

    public function store(StoreGymRequest $request): RedirectResponse
    {
        $gym = DB::transaction(function () use ($request): Gym {
            $gym = Gym::create([
                ...$request->validated(),
                'owner_id' => $request->user()->id,
                'verification_status' => 'pending',
            ]);
            $this->audit($request->user()->id, 'gym.created', $gym->gym_id);

            return $gym;
        });

        return redirect()->route('owner.gyms.edit', $gym)
            ->with('success', 'تم إنشاء النادي وحفظه بحالة قيد المراجعة.');
    }

    public function show(Gym $gym): RedirectResponse
    {
        $this->authorize('view', $gym);

        return redirect()->route('owner.gyms.edit', $gym);
    }

    public function edit(Gym $gym): View
    {
        $this->authorize('update', $gym);
        $gym->load(['activities', 'facilities', 'memberships', 'workingHours', 'images', 'reviews.user']);
        $activities = Activity::query()->orderBy('activity_name')->get();
        $facilities = Facility::query()->orderBy('facility_name')->get();

        return view('optifit.owner.gyms.form', compact('gym', 'activities', 'facilities'));
    }

    public function update(UpdateGymRequest $request, Gym $gym): RedirectResponse
    {
        DB::transaction(function () use ($request, $gym): void {
            $gym->fill($request->validated());
            if ($gym->isDirty()) {
                $gym->verification_status = 'pending';
                $gym->save();
                $this->audit($request->user()->id, 'gym.updated', $gym->gym_id);
            }
        });

        return back()->with('success', 'تم تحديث بيانات النادي وإعادته إلى قيد المراجعة.');
    }

    public function destroy(DeleteGymRequest $request, Gym $gym): RedirectResponse
    {
        $paths = $gym->images()->pluck('image_path')->all();
        $gymId = (int) $gym->gym_id;

        DB::transaction(function () use ($request, $gym, $gymId): void {
            $gym->delete();
            $this->audit($request->user()->id, 'gym.deleted', $gymId);
        });

        Storage::disk('public')->delete($paths);

        return redirect()->route('owner.gyms.index')
            ->with('success', 'تم حذف النادي والبيانات المرتبطة به.');
    }

    private function audit(int $userId, string $action, int $entityId): void
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => 'gym',
            'entity_id' => $entityId,
        ]);
    }
}
