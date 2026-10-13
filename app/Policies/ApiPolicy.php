<?php

namespace App\Policies;

use App\Models\Api;
use App\Models\User;

class ApiPolicy
{
    /**
     * Determine whether the user can view the API monitor.
     */
    public function view(User $user, Api $api): bool
    {
        return (int) $api->user_id === (int) $user->id;
    }
}
