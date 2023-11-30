<?php

namespace App\Traits;

use App\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait ValidationTrait
{
    /**
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    public function failedValidationResponse(Validator $validator): void
    {
        throw ValidationException::withMessages([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ]);
    }

    /**
     * @param PostRepositoryInterface $postRepository
     * @return array|string[]
     */
    public function slugValidation(PostRepositoryInterface $postRepository): array
    {
        $postId = substr(strrchr(rtrim(request()->getRequestUri(), '/'), '/'), 1);

        if ($postRepository->checkSlugDuplicate($postId, $this->slug) === 1) {
            return [
                'slug' => 'required|max:100|' . Rule::unique('posts')->ignore($postId),
            ];
        }

        return [
            'slug' => 'required|max:100|unique:posts',
        ];
    }
}
