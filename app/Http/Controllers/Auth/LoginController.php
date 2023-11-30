<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\TokenServiceInterface;
use App\Traits\AuthResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use AuthResponseTrait;

    /**
     * @param LoginRequest $loginRequest
     * @param TokenServiceInterface $tokenService
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(
        LoginRequest $loginRequest,
        TokenServiceInterface $tokenService
    ) : JsonResponse {
        $loginRequest->authenticate();

        if (Auth::user()) {
            return $this->grant(
                $tokenService->accessToken(
                    Auth::user()
                ), Response::HTTP_OK
            );
        }
    }
}
