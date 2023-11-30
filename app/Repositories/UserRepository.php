<?php

namespace App\Repositories;

use App\Http\Requests\RegistrationRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param RegistrationRequest $registrationRequest
     * @return User
     */
    public function register(RegistrationRequest $registrationRequest): User
    {
        return User::factory()->create([
            'name' => $registrationRequest->name,
            'email' => $registrationRequest->email,
            'password' => Hash::make($registrationRequest->password)
        ]);
    }
}
