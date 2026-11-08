<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteGymRequest extends FormRequest
{
    public function authorize(): bool
    {
        $gym = $this->route('gym');

        return $gym && ($this->user()?->can('delete', $gym) ?? false);
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
            'confirmation_name' => [
                'required',
                'string',
                Rule::in([(string) $this->route('gym')?->gym_name]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'confirmation_name.in' => 'اكتب اسم النادي كما يظهر تمامًا لتأكيد الحذف.',
        ];
    }
}
