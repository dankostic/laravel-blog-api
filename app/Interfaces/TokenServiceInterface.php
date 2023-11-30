<?php

namespace App\Interfaces;

use App\Models\User;

interface TokenServiceInterface
{
    /**
     * @param User $user
     * @return string
     */
    public function accessToken(User $user): string;
}
