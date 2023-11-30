<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PostEnum;
use App\Traits\ValidationTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreatePostRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'title' => 'required|max:100',
            'content' => 'required|max:2000',
            'slug' => 'required|max:100|unique:posts',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => !empty($this->slug) ? $this->slug : Str::slug($this->title),
        ]);
    }

    /**
     * @param Validator $validator
     * @return JsonResponse
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): JsonResponse
    {
        return $this->failedValidationResponse($validator);
    }
}
