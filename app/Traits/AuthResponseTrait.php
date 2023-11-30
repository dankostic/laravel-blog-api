<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait AuthResponseTrait
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
    public function revoke(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Logout successfully'
        ], Response::HTTP_OK);
    }
}
