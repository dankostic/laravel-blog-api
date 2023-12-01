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
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login to system.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Login to system",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *             @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *             @OA\JsonContent(),
     *     )
     * )
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
