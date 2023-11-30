<?php

namespace Tests\Feature;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Danko',
            'email' => 'dankokostic@yahoo.com',
        ]);

        $this->post = Post::factory()->create();
    }

    # INDEX

    public function test_api_returns_all_posts_unauthorized(): void
    {
        $this->getJson(route('posts.index'))
            ->assertUnauthorized();
    }

    public function test_api_returns_all_posts_successfully(): void
    {
        $posts = Post::factory(5)->create();
        $collection = new PostCollection($posts);
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('posts.index'))
            ->assertOk()
            ->json('data');

        $this->assertCount(5, $posts->toArray());
        $this->assertCount(6, $response);
        $this->assertEquals($collection[4]->title, $posts[4]->title);
        $this->assertIsArray($response);
    }

    # SHOW

    public function test_api_returns_single_posts_unauthorized(): void
    {
        $this->getJson(route('posts.show', 1))
            ->assertUnauthorized();
    }

    public function test_api_returns_single_post_not_found(): void
    {
        $this->actingAs($this->user, 'api')
            ->getJson(route('posts.show', 5))
            ->assertNotFound();
    }

    public function test_api_returns_single_post_successfully(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('posts.show', $this->post->id))
            ->assertOk()
            ->json('data');

        $this->assertEquals($response['slug'], $this->post->slug);
    }

    # STORE

    public function test_api_store_new_post_successfully(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('posts.store', $this->storePostDataProvider()))
            ->assertCreated()
            ->json('data');

        $this->assertDatabaseHas('posts', ['title' => $this->storePostDataProvider()['title']])
            ->assertEquals($response['content'], $this->storePostDataProvider()['content']);
    }

    public function test_api_store_new_post_without_slug_successfully(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('posts.store', ['title' => 'New title', 'content' => 'new content']))
            ->assertCreated()
            ->json('data');

        $this->assertEquals('new-title', $response['slug']);
    }

    public function test_api_while_storing_new_post_with_fields_required_missing(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('posts.store'))
            ->assertUnprocessable()
            ->json();

        $this->assertEquals('The given data was invalid.', $response['message']);
        $this->assertEquals('The title field is required.', $response['errors']['data'][0]['title'][0]);
    }

    # UPDATE

    public function test_api_update_post_successfully(): void
    {
        $this->actingAs($this->user, 'api')
            ->patchJson(route('posts.update', $this->post), ['slug' => 'updated'])
            ->assertOk();

        $this->assertDatabaseHas('posts', ['id' => $this->post->id, 'slug' => 'updated']);
    }

    public function test_api_update_post_forbidden(): void
    {
        Passport::actingAs(User::factory()->create([
            'name' => 'Other user',
            'email' => 'otherUser@gmail.com',
        ]));

        $this->patchJson(route('posts.update', $this->post), ['slug' => 'updated'])
            ->assertForbidden();
    }

    public function test_api_while_updating_post_with_fields_required_missing(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->patchJson(route('posts.update', $this->post), ['title' => 'updated'])
            ->assertUnprocessable()
            ->json();

        $this->assertEquals('Validation errors', $response['errors']['message'][0]);
        $this->assertEquals('The slug field is required.', $response['errors']['data'][0]['slug'][0]);
    }

    # DELETE

    public function test_api_delete_post_successfully(): void
    {
        $this->actingAs($this->user, 'api')
            ->deleteJson(route('posts.destroy', $this->post->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('posts', ['content' => $this->post->content]);
    }

    public function test_api_delete_post_forbidden(): void
    {
        Passport::actingAs(User::factory()->create([
            'name' => 'Other user',
            'email' => 'otherUser@gmail.com',
        ]));

        $this->deleteJson(route('posts.destroy', $this->post->id))
            ->assertForbidden();
    }

    /**
     * @return string[]
     */
    private function storePostDataProvider(): array
    {
        return [
            'title' => 'My title',
            'content' => 'Completely new content',
            'slug' => 'my-slug'
        ];
    }
}
