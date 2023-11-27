<?php

namespace App\Http\Requests;

use App\Traits\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegistrationRequest extends FormRequest
{
    use ValidationException;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8'
        ];
    }

    /**
     * @param Validator $validator
     * @return HttpResponseException
     */
    public function failedValidation(Validator $validator): HttpResponseException
    {
        return $this->failedValidationResponse($validator);
    }
}
