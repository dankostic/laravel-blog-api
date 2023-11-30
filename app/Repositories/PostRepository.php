<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface
{
    /**
     * @param CreatePostRequest $createPostRequest
     * @return Post
     */
    public function create(CreatePostRequest $createPostRequest): Post
    {
        return Post::factory()->create([
            'user_id' => Auth::user()->id,
            'title' => $createPostRequest->get('title'),
            'content' => $createPostRequest->get('content'),
            'slug' => $createPostRequest->get('slug')
        ]);
    }

    /**
     * @param UpdatePostRequest $updatePostRequest
     * @param Post $post
     * @return Post
     */
    public function update(UpdatePostRequest $updatePostRequest, Post $post): Post
    {
        $post->update($updatePostRequest->only(['slug']));

        return $post;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function delete(Post $post): void
    {
        $post->delete();
    }

    /**
     * @param int $id
     * @param string|null $slug
     * @return int
     */
    public function checkSlugDuplicate(int $id, ?string $slug): int
    {
        return Post::where(['id' => $id, 'slug' => $slug])->count();
    }
}
