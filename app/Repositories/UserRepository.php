<?php

namespace App\Repositories;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param UserRegistrationRequest $registrationRequest
     * @return User
     */
    public function register(UserRegistrationRequest $registrationRequest): User
    {
        return User::create([
            'name' => $registrationRequest->name,
            'email' => $registrationRequest->email,
            'password' => Hash::make($registrationRequest->password)
        ]);
    }

    /**
     * @param UserLoginRequest $loginRequest
     * @return User|bool
     */
    public function login(UserLoginRequest $loginRequest): User|bool
    {
        $user = User::where('email', $loginRequest->email)->firstorfail();

        if ($user) {
            return $this->checkUserPassword($loginRequest, $user) === true ? $user : false;
        }

        return false;
    }

    /**
     * @param UserLoginRequest $loginRequest
     * @param User $user
     * @return bool
     */
    private function checkUserPassword(UserLoginRequest $loginRequest, User $user): bool
    {
        return Hash::check($loginRequest->password, $user->password);
    }
}
