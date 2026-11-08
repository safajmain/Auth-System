<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGymImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $gym = $this->route('gym');

        return $gym && ($this->user()?->can('update', $gym) ?? false);
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
