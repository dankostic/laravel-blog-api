<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;

interface PostRepositoryInterface
{
    /**
     * @param CreatePostRequest $createPostRequest
     * @return Post
     */
    public function create(CreatePostRequest $createPostRequest): Post;

    /**
     * @param UpdatePostRequest $updatePostRequest
     * @param Post $post
     * @return Post
     */
    public function update(UpdatePostRequest $updatePostRequest, Post $post): Post;

    /**
     * @param Post $post
     * @return void
     */
    public function delete(Post $post): void;
}
