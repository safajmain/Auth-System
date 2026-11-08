<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGymStatusRequest;
use App\Models\AuditLog;
use App\Models\Gym;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminGymController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless((int) $request->user()->role_id === 3, 403);
        $status = $request->validate([
            'status' => ['nullable', \Illuminate\Validation\Rule::in(['pending', 'approved', 'rejected'])],
        ])['status'] ?? null;
        $gyms = Gym::query()
            ->with(['owner', 'activities', 'facilities', 'memberships', 'images'])
            ->withCount(['reviews', 'favoritedByUsers'])
            ->withAvg('reviews', 'rating')
            ->when($status, fn ($query) => $query->where('verification_status', $status))
            ->latest('gym_id')->paginate(20)->withQueryString();

        return view('optifit.admin.gyms.index', compact('gyms', 'status'));
    }

    public function show(Request $request, Gym $gym): View
    {
        abort_unless((int) $request->user()->role_id === 3, 403);
        $gym->load([
            'owner', 'activities', 'facilities', 'memberships',
            'workingHours', 'images', 'reviews.user',
        ])->loadCount('favoritedByUsers')->loadAvg('reviews', 'rating');

        return view('optifit.admin.gyms.show', compact('gym'));
    }

    public function updateStatus(UpdateGymStatusRequest $request, Gym $gym): RedirectResponse
    {
        $oldStatus = $gym->verification_status;
        $gym->update(['verification_status' => $request->validated('status')]);
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => "gym.status.{$oldStatus}.to.{$gym->verification_status}",
            'entity_type' => 'gym',
            'entity_id' => $gym->gym_id,
        ]);

        return back()->with('success', 'تم تحديث حالة اعتماد النادي.');
    }
}
