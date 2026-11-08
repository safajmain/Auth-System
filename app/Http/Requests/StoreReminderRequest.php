<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $reminder = $this->route('reminder');

        return (int) $user?->role_id === 1
            && (! $reminder || (int) $reminder->user_id === (int) $user->id);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'repeat_type' => ['required', Rule::in(['once', 'daily', 'weekly'])],
            'day_of_week' => ['nullable', Rule::in([
                'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',
            ])],
            'reminder_time' => ['required', 'date_format:H:i'],
        ];
    }
}
