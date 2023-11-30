<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Traits\AuthResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use AuthResponseTrait;

    /**
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    /**
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        return new PostCollection(Post::all());
    }

    /**
     * @param CreatePostRequest $createPostRequest
     * @return PostResource
     */
    public function store(CreatePostRequest $createPostRequest): PostResource
    {
        return new PostResource($this->postRepository->create($createPostRequest));
    }

    /**
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * @param UpdatePostRequest $updatePostRequest
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    public function update(UpdatePostRequest $updatePostRequest, Post $post): PostResource
    {
        $this->authorize('update', $post);

        return new PostResource($this->postRepository->update($updatePostRequest, $post));
    }

    /**
     * @param Post $post
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);
        $this->postRepository->delete($post);

        return new JsonResponse([
            null
        ], Response::HTTP_NO_CONTENT);
    }
}
