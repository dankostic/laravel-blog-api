<?php

namespace App\Interfaces;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param RegistrationRequest $registrationRequest
     * @return User
     */
    public function register(RegistrationRequest $registrationRequest): User;
}
