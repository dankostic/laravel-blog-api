<?php

namespace App\Services;

use App\Enums\AuthEnum;
use App\Models\User;

class TokenService
{
    /**
     * @param User $user
     * @return string
     */
    public function accessToken(User $user): string
    {
        return $user->createToken(AuthEnum::AUTH_TOKEN->value)->accessToken;
    }
}
