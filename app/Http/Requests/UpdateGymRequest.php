<?php

namespace App\Http\Requests;

class UpdateGymRequest extends StoreGymRequest
{
    public function authorize(): bool
    {
        $gym = $this->route('gym');

        return $gym && ($this->user()?->can('update', $gym) ?? false);
    }
}
