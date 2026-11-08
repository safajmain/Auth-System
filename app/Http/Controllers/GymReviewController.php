<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Gym;
use App\Models\GymReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GymReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Gym $gym): RedirectResponse
    {
        GymReview::updateOrCreate(
            ['user_id' => $request->user()->id, 'gym_id' => $gym->gym_id],
            $request->validated()
        );

        return back()->with('success', 'تم حفظ تقييمك.');
    }

    public function destroy(Request $request, Gym $gym, GymReview $review): RedirectResponse
    {
        abort_unless((int) $request->user()->role_id === 1, 403);
        abort_unless(
            (int) $review->user_id === (int) $request->user()->id
            && (int) $review->gym_id === (int) $gym->gym_id,
            403
        );
        $review->delete();

        return back()->with('success', 'تم حذف تقييمك.');
    }
}
