<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGymStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $gym = $this->route('gym');

        return $gym && ($this->user()?->can('review', $gym) ?? false);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['approved', 'rejected', 'pending'])],
        ];
    }
}
