<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        $gym = $this->route('gym');

        return $gym && ($this->user()?->can('update', $gym) ?? false);
    }

    public function rules(): array
    {
        return [
            'activities' => ['sometimes', 'array'],
            'activities.*' => ['integer', 'distinct', 'exists:activities,activity_id'],
            'facilities' => ['sometimes', 'array'],
            'facilities.*' => ['integer', 'distinct', 'exists:facilities,facility_id'],
        ];
    }
}
