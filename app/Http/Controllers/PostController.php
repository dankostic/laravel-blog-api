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
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     operationId="postIndex",
     *     summary="Get all posts for REST API",
     *     description="Get all posts for REST API",
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        return new PostCollection(Post::all());
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     operationId="postStore",
     *     summary="Store post",
     *     description="Store post",
     *     security={{"passport":{}}},
     *     @OA\RequestBody(
     *          description="Store post",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="title",
     *                      description="Post title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      description="Post content",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                       property="slug",
     *                       description="Post slug",
     *                       type="string"
     *                   ),
     *                  required={"title", "content"}
     *              )
     *          ),
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Successfully created",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *              @OA\JsonContent(),
     *      )
     * )
     * @param CreatePostRequest $createPostRequest
     * @return PostResource
     */
    public function store(CreatePostRequest $createPostRequest): PostResource
    {
        return new PostResource($this->postRepository->create($createPostRequest));
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     operationId="postShow",
     *     summary="Get Post Details",
     *     description="Get Post Details",
     *     security={ {"passport": {} }},
     *     @OA\Parameter(
     *        description="ID of Post",
     *        in="path",
     *        name="id",
     *        required=true,
     *        example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )
     * ),
     *     @OA\Response(
     *           response="200",
     *           description="Successful operation",
     *            @OA\JsonContent()
     *        ),
     *      @OA\Response(
     *           response=404,
     *           description="Record not found",
     *               @OA\JsonContent(),
     *       )
     * )
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * @OA\Patch(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     operationId="postUpdate",
     *     summary="Update post",
     *     description="Update post",
     *     security={{"passport":{}}},
     *      @OA\Parameter(
     *         description="ID of Post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *     @OA\Schema(
     *        type="integer",
     *        format="int64"
     *     )
     *  ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="slug",
     *           description="Update slug",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     *     @OA\Response(
     *            response="200",
     *            description="Successful operation",
     *             @OA\JsonContent()
     *         ),
     *       @OA\Response(
     *            response=404,
     *            description="Record not found",
     *                @OA\JsonContent(),
     *        ),
     *       @OA\Response(
     *             response=403,
     *             description="Error: Forbidden",
     *                 @OA\JsonContent(),
     *         ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *               @OA\JsonContent(),
     *       )
     * )
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
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     operationId="postDelete",
     *     summary="Delete Post",
     *     description="Delete Post",
     *     security={ {"passport": {} }},
     *     @OA\Parameter(
     *        description="ID of Post",
     *        in="path",
     *        name="id",
     *        required=true,
     *        example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )
     * ),
     *     @OA\Response(
     *           response="204",
     *           description="No content",
     *            @OA\JsonContent()
     *        ),
     *     @OA\Response(
     *              response=403,
     *              description="Error: Forbidden",
     *                  @OA\JsonContent(),
     *          ),
     *      @OA\Response(
     *           response=404,
     *           description="Record not found",
     *               @OA\JsonContent(),
     *       )
     * )
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
