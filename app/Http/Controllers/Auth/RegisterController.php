<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Interfaces\TokenServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Traits\AuthResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use AuthResponseTrait;

    /**
     * @param RegistrationRequest $registrationRequest
     * @param UserRepositoryInterface $userRepository
     * @param TokenServiceInterface $tokenService
     * @return JsonResponse
     */
    public function __invoke(
        RegistrationRequest $registrationRequest,
        UserRepositoryInterface $userRepository,
        TokenServiceInterface $tokenService
    ) : JsonResponse {
        return $this->grant(
            $tokenService->accessToken(
                $userRepository->register($registrationRequest)
            ), Response::HTTP_CREATED
        );
    }
}
