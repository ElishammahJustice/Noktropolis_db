<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasAbility('view_users');
    }

    public function view(User $user, User $model)
    {
        return $user->hasAbility('view_user');
    }

    public function create(User $user)
    {
        return $user->hasAbility('create_user');
    }

    public function update(User $user, User $model)
    {
        return $user->hasAbility('edit_user');
    }

    public function delete(User $user, User $model)
    {
        return $user->hasAbility('delete_user');
    }

    // Custom: suspend
    public function suspend(User $user, User $model)
    {
        return $user->hasAbility('suspend_user');
    }
}
