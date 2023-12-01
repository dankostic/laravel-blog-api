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
     * @OA\POST(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register",
     *     description="Register to system.",
     *     operationId="register",
     *     @OA\RequestBody(
     *         description="Register to blog api",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User Name",
     *                     type="string",
     *                     example="Blog Api"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string",
     *                     example="blog@api.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string",
     *                     example="12345678"
     *                 ),
     *                 required={"name", "email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successfully created",
     *             @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *             @OA\JsonContent(),
     *     )
     * )
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
