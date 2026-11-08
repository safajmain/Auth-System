<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Models\Reminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        $reminders = $request->user()->reminders()->with('schedules')
            ->orderByDesc('notification_id')->get();

        return view('optifit.user.reminders.index', compact('reminders'));
    }

    public function store(StoreReminderRequest $request): RedirectResponse
    {
        $this->persist($request);

        return back()->with('success', 'تم إنشاء التذكير.');
    }

    public function update(StoreReminderRequest $request, Reminder $reminder): RedirectResponse
    {
        $this->owned($request, $reminder);
        $this->persist($request, $reminder);

        return back()->with('success', 'تم تحديث التذكير.');
    }

    public function toggle(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->owned($request, $reminder);
        $reminder->update(['is_active' => ! $reminder->is_active]);

        return back()->with('success', $reminder->is_active ? 'تم تفعيل التذكير.' : 'تم إيقاف التذكير.');
    }

    public function destroy(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->owned($request, $reminder);
        $reminder->delete();

        return back()->with('success', 'تم حذف التذكير.');
    }

    private function persist(StoreReminderRequest $request, ?Reminder $reminder = null): Reminder
    {
        $data = $request->validated();

        return DB::transaction(function () use ($request, $data, $reminder): Reminder {
            $reminder ??= new Reminder(['user_id' => $request->user()->id]);
            $reminder->fill(collect($data)->except(['day_of_week', 'reminder_time'])->all());
            $reminder->save();
            $reminder->schedules()->delete();
            $reminder->schedules()->create([
                'day_of_week' => $data['repeat_type'] === 'weekly' ? ($data['day_of_week'] ?? null) : null,
                'reminder_time' => $data['reminder_time'],
            ]);

            return $reminder;
        });
    }

    private function owned(Request $request, Reminder $reminder): void
    {
        abort_unless(
            (int) $request->user()->role_id === 1
            && (int) $reminder->user_id === (int) $request->user()->id,
            403
        );
    }
}
