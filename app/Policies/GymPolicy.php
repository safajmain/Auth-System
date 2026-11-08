<?php

namespace App\Policies;

use App\Models\Gym;
use App\Models\User;

class GymPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array((int) $user->role_id, [1, 2, 3], true);
    }

    public function view(User $user, Gym $gym): bool
    {
        return (int) $user->role_id === 3
            || ((int) $user->role_id === 2 && (int) $gym->owner_id === (int) $user->id)
            || ((int) $user->role_id === 1 && $gym->verification_status === 'approved');
    }

    public function create(User $user): bool
    {
        return (int) $user->role_id === 2;
    }

    public function update(User $user, Gym $gym): bool
    {
        return (int) $user->role_id === 2
            && (int) $gym->owner_id === (int) $user->id;
    }

    public function delete(User $user, Gym $gym): bool
    {
        return $this->update($user, $gym);
    }

    public function review(User $user, Gym $gym): bool
    {
        return (int) $user->role_id === 3;
    }
}
