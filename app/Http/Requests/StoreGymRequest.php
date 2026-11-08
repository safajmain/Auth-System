<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGymRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Gym::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'gym_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website_url' => ['nullable', 'url:http,https', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'gym_name' => 'اسم النادي',
            'description' => 'وصف النادي',
            'city' => 'المدينة',
            'area' => 'المنطقة',
            'address' => 'العنوان',
            'latitude' => 'خط العرض',
            'longitude' => 'خط الطول',
            'phone' => 'رقم الهاتف',
            'website_url' => 'الموقع الإلكتروني',
        ];
    }
}
