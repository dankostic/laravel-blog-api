<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Repositories\UserRepository;
use App\Services\TokenService;
use App\Traits\AuthResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use AuthResponse;
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenRepository
    ) {
    }

    /**
     * @param UserRegistrationRequest $registrationRequest
     * @return JsonResponse
     */
    public function register(UserRegistrationRequest $registrationRequest): JsonResponse
    {
        return $this->grant(
            $this->tokenRepository->accessToken(
            $this->userRepository->register($registrationRequest)
        ), Response::HTTP_CREATED);
    }

    /**
     * @param UserLoginRequest $loginRequest
     * @return JsonResponse
     */
    public function login(UserLoginRequest $loginRequest): JsonResponse
    {
         $user = $this->userRepository->login($loginRequest);

         if ($user) {
             return $this->grant($this->tokenRepository->accessToken($user), Response::HTTP_OK);
         }

        return $this->unauthorized();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return $this->revoke();
    }
}
