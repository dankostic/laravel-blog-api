<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuthEnum;
use App\Interfaces\TokenServiceInterface;
use App\Models\User;

class TokenService implements TokenServiceInterface
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
