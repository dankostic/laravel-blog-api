<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait AuthResponse
{
    /**
     * @param string $token
     * @param int $status
     * @return JsonResponse
     */
    public function grant(string $token, int $status): JsonResponse
    {
        return new JsonResponse([
            'token' => $token
        ], $status);
    }

    /**
     * @return JsonResponse
     */
    public function unauthorized(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'You are not authorized'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return JsonResponse
     */
    public function revoke(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Logout successfully'
        ], Response::HTTP_OK);
    }
}
