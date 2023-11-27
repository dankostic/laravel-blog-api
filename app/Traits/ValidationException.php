<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationException
{
    /**
     * @param Validator $validator
     * @return HttpResponseException
     */
    public function failedValidationResponse(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ]
        ));
    }
}
