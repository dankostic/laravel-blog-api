<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Interfaces\PostRepositoryInterface;
use App\Traits\ValidationTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UpdatePostRequest extends FormRequest
{
    use ValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(PostRepositoryInterface $postRepository): array
    {
        return $this->slugValidation($postRepository);
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->failedValidationResponse($validator);
    }
}
