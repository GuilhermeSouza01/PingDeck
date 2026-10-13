<?php

namespace App\Actions\Api;

use App\Models\Api;
use App\Models\User;

class CreateApi
{
    /**
     * Persist a new API monitor owned by the given user.
     *
     * The "user_id" is derived from the relation, so it always comes from the
     * authenticated user and can never be overridden by the input attributes.
     *
     * @param  array{name: string, url: string, method: string, expected_status: int, timeout: int, description: string|null, is_active: bool}  $attributes
     */
    public function handle(User $user, array $attributes): Api
    {
        return $user->apis()->create($attributes);
    }
}
