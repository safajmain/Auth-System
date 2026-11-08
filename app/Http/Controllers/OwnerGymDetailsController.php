<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGymImageRequest;
use App\Http\Requests\UpdateGymCatalogRequest;
use App\Models\AuditLog;
use App\Models\Gym;
use App\Models\GymImage;
use App\Models\GymMembership;
use App\Models\GymWorkingHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class OwnerGymDetailsController extends Controller
{
    public function facilities(UpdateGymCatalogRequest $request, Gym $gym): RedirectResponse
    {
        $gym->facilities()->sync($request->validated('facilities', []));
        $this->markPending($gym);
        $this->audit($request, 'gym.facilities.updated', $gym);

        return back()->with('success', 'تم تحديث مرافق النادي.');
    }

    public function activities(UpdateGymCatalogRequest $request, Gym $gym): RedirectResponse
    {
        $gym->activities()->sync($request->validated('activities', []));
        $this->markPending($gym);
        $this->audit($request, 'gym.activities.updated', $gym);

        return back()->with('success', 'تم تحديث أنشطة النادي.');
    }

    public function storeMembership(Request $request, Gym $gym): RedirectResponse
    {
        $this->authorize('update', $gym);
        $data = $request->validate([
            'duration_months' => ['required', 'integer', 'between:1,36'],
            'price_jod' => ['required', 'numeric', 'between:0,999999.99'],
        ]);

        $membership = $gym->memberships()->updateOrCreate(
            ['duration_months' => $data['duration_months']],
            ['price_jod' => $data['price_jod']]
        );
        $this->markPending($gym);
        $this->audit($request, 'gym.membership.saved', $gym, $membership->membership_id);

        return back()->with('success', 'تم حفظ الاشتراك والسعر.');
    }

    public function destroyMembership(Request $request, Gym $gym, GymMembership $membership): RedirectResponse
    {
        $this->authorize('update', $gym);
        abort_unless((int) $membership->gym_id === (int) $gym->gym_id, 404);
        $membershipId = (int) $membership->membership_id;
        $membership->delete();
        $this->markPending($gym);
        $this->audit($request, 'gym.membership.deleted', $gym, $membershipId);

        return back()->with('success', 'تم حذف الاشتراك.');
    }

    public function storeHour(Request $request, Gym $gym): RedirectResponse
    {
        $this->authorize('update', $gym);
        $data = $request->validate([
            'day_of_week' => ['required', Rule::in([
                'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',
            ])],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
        ]);

        $hour = $gym->workingHours()->create($data);
        $this->markPending($gym);
        $this->audit($request, 'gym.hours.created', $gym, $hour->working_hour_id);

        return back()->with('success', 'تمت إضافة ساعات العمل.');
    }

    public function destroyHour(Request $request, Gym $gym, GymWorkingHour $hour): RedirectResponse
    {
        $this->authorize('update', $gym);
        abort_unless((int) $hour->gym_id === (int) $gym->gym_id, 404);
        $hourId = (int) $hour->working_hour_id;
        $hour->delete();
        $this->markPending($gym);
        $this->audit($request, 'gym.hours.deleted', $gym, $hourId);

        return back()->with('success', 'تم حذف فترة العمل.');
    }

    public function storeImage(StoreGymImageRequest $request, Gym $gym): RedirectResponse
    {
        $path = $request->file('image')->store("gyms/{$gym->gym_id}", 'public');

        try {
            $image = $gym->images()->create(['image_path' => $path]);
            $this->markPending($gym);
            $this->audit($request, 'gym.image.created', $gym, $image->image_id);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);
            throw $exception;
        }

        return back()->with('success', 'تم رفع صورة النادي.');
    }

    public function destroyImage(Request $request, Gym $gym, GymImage $image): RedirectResponse
    {
        $this->authorize('update', $gym);
        abort_unless((int) $image->gym_id === (int) $gym->gym_id, 404);
        $path = $image->image_path;
        $imageId = (int) $image->image_id;
        $image->delete();
        Storage::disk('public')->delete($path);
        $this->markPending($gym);
        $this->audit($request, 'gym.image.deleted', $gym, $imageId);

        return back()->with('success', 'تم حذف الصورة.');
    }

    private function markPending(Gym $gym): void
    {
        if ($gym->verification_status !== 'pending') {
            $gym->forceFill(['verification_status' => 'pending'])->save();
        }
    }

    private function audit(Request $request, string $action, Gym $gym, ?int $relatedId = null): void
    {
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'entity_type' => $relatedId ? 'gym_item' : 'gym',
            'entity_id' => $relatedId ?? $gym->gym_id,
        ]);
    }
}
