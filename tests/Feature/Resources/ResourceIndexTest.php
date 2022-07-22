<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Resources;

use SertxuDeveloper\Lyra\Tests\Models\Post;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\Resources\Users;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceIndexTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        $this->registerDefaultResources();
    }

    /**
     * Check can list a resource.
     * By default, the last inserted will be the first one.
     *
     * @return void
     */
    public function test_can_list_a_resource(): void {
        User::factory(2)->create();
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['users']));

        $response->assertSuccessful();

        $this->assertIsArray($response->json('header'));
        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('meta'));
        $this->assertIsArray($response->json('labels'));
        $this->assertIsArray($response->json('perPageOptions'));
        $this->assertIsArray($response->json('actions'));
        $this->assertIsArray($response->json('meta'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals(Users::$perPageOptions, $response->json('perPageOptions'));

        $this->assertEquals($user->id, $response->json('data.0.key'));

        $fields = $response->json('data.0.fields');
        $nameField = collect($fields)->where('key', 'name')->first();
        $this->assertEquals($user->name, $nameField['value']);

        $response->assertJsonCount(3, 'data');
    }

    /**
     * Check can search for resources.
     *
     * @return void
     */
    public function test_can_search_for_resources(): void {
        User::factory(2)->create();
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->getJson(route("$this->API_PREFIX.resources.index", ['users', "q=$user->email"]));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals($user->id, $response->json('data.0.key'));
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Check a resource is not shown if it has been soft-deleted.
     *
     * @return void
     */
    public function test_hides_resources_that_are_soft_deleted(): void {
        $user = User::factory()->create();
        $posts = Post::factory(2)->create();

        $deleted = Post::factory()->create();
        $deleted->delete();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts']));

        $response->assertSuccessful();

        $this->assertEquals(2, $response->json('meta.total'));
        $this->assertEquals($posts->last()->id, $response->json('data.0.key'));
        $response->assertJsonCount(2, 'data');
    }

    /**
     * Check a soft-deleted resource is shown if requested.
     *
     * @return void
     */
    public function test_includes_soft_deleted_resources_if_requested(): void {
        $user = User::factory()->create();
        Post::factory(2)->create();

        $deleted = Post::factory()->create();
        $deleted->delete();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'trashed=with']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($deleted->id, $response->json('data.0.key'));
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Check soft-deleted resources are the only shown if requested.
     *
     * @return void
     */
    public function test_only_soft_deleted_resources_if_requested(): void {
        $user = User::factory()->create();
        Post::factory(2)->create();

        $deleted = Post::factory()->create();
        $deleted->delete();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'trashed=only']));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals($deleted->id, $response->json('data.0.key'));
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Check a resource are obtained it the correct default order.
     *
     * @return void
     */
    public function test_can_order_resources_default(): void {
        $user = User::factory()->create();

        $postA = Post::factory()->create(['title' => 'This is the post A']);
        $postB = Post::factory()->create(['title' => 'This is the post B']);
        $postC = Post::factory()->create(['title' => 'This is the post C']);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($postC->id, $response->json('data.0.key'));
        $this->assertEquals($postB->id, $response->json('data.1.key'));
        $this->assertEquals($postA->id, $response->json('data.2.key'));
    }

    /**
     * Check resources are obtained in the correct asc order.
     *
     * @return void
     */
    public function test_can_order_resources_asc(): void {
        $user = User::factory()->create();

        $postA = Post::factory()->create(['title' => 'This is the post A']);
        $postB = Post::factory()->create(['title' => 'This is the post B']);
        $postC = Post::factory()->create(['title' => 'This is the post C']);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'sortBy=title', 'sortOrder=asc']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($postA->id, $response->json('data.0.key'));
        $this->assertEquals($postB->id, $response->json('data.1.key'));
        $this->assertEquals($postC->id, $response->json('data.2.key'));
    }

    /**
     * Check resources are obtained in the correct desc order.
     *
     * @return void
     */
    public function test_can_order_resources_desc(): void {
        $user = User::factory()->create();

        $postA = Post::factory()->create(['title' => 'This is the post A']);
        $postB = Post::factory()->create(['title' => 'This is the post B']);
        $postC = Post::factory()->create(['title' => 'This is the post C']);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'sortBy=title', 'sortOrder=desc']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($postC->id, $response->json('data.0.key'));
        $this->assertEquals($postB->id, $response->json('data.1.key'));
        $this->assertEquals($postA->id, $response->json('data.2.key'));
    }

    /**
     * Check resources are not sorted if the sort parameters are not valid.
     *
     * @return void
     */
    public function test_can_not_order_resources_if_sort_parameters_are_not_valid(): void {
        $user = User::factory()->create();

        $postA = Post::factory()->create(['title' => 'This is the post A']);
        $postB = Post::factory()->create(['title' => 'This is the post B']);
        $postC = Post::factory()->create(['title' => 'This is the post C']);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'sortBy=title,subtitle', 'sortOrder=asc']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($postA->id, $response->json('data.0.key'));
        $this->assertEquals($postB->id, $response->json('data.1.key'));
        $this->assertEquals($postC->id, $response->json('data.2.key'));
    }

    /**
     * Check resources are not sorted if the direction is not valid.
     *
     * @return void
     */
    public function test_can_not_order_resources_if_direction_is_not_valid(): void {
        $user = User::factory()->create();

        $postA = Post::factory()->create(['title' => 'This is the post A']);
        $postB = Post::factory()->create(['title' => 'This is the post B']);
        $postC = Post::factory()->create(['title' => 'This is the post C']);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'sortBy=title', 'sortOrder=ascd']));

        $response->assertSuccessful();

        $this->assertEquals(3, $response->json('meta.total'));
        $this->assertEquals($postA->id, $response->json('data.0.key'));
        $this->assertEquals($postB->id, $response->json('data.1.key'));
        $this->assertEquals($postC->id, $response->json('data.2.key'));
    }

    /**
     * Check can limit resources shown per page.
     *
     * @return void
     */
    public function test_can_limit_resources_per_page(): void {
        $user = User::factory()->create();

        Post::factory(5)->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'perPage=2']));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.current_page'));
        $this->assertEquals(1, $response->json('meta.from'));
        $this->assertEquals(2, $response->json('meta.to'));
        $this->assertEquals(2, $response->json('meta.per_page'));
        $this->assertEquals(5, $response->json('meta.total'));
        $response->assertJsonCount(2, 'data');
    }

    /**
     * Check can navigate to next page.
     *
     * @return void
     */
    public function test_can_navigate_to_next_page(): void {
        $user = User::factory()->create();

        Post::factory(5)->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'perPage=2', 'page=2']));

        $response->assertSuccessful();

        $this->assertEquals(2, $response->json('meta.current_page'));
        $this->assertEquals(3, $response->json('meta.from'));
        $this->assertEquals(4, $response->json('meta.to'));
        $this->assertEquals(2, $response->json('meta.per_page'));
        $this->assertEquals(5, $response->json('meta.total'));
        $response->assertJsonCount(2, 'data');
    }

    /**
     * Check get pagination with multiple pages.
     *
     * @return void
     */
    public function test_can_get_pagination_with_multiple_pages(): void {
        $user = User::factory()->create();

        Post::factory(1)->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'perPage=1']));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.current_page'));
        $this->assertEquals(1, $response->json('meta.from'));
        $this->assertEquals(1, $response->json('meta.to'));
        $this->assertEquals(1, $response->json('meta.per_page'));
        $this->assertEquals(1, $response->json('meta.total'));
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Check can get pagination with no resources.
     *
     * @return void
     */
    public function test_can_get_pagination_with_no_resources(): void {
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index", ['posts', 'perPage=1']));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.current_page'));
        $this->assertEquals(0, $response->json('meta.from'));
        $this->assertEquals(0, $response->json('meta.to'));
        $this->assertEquals(1, $response->json('meta.per_page'));
        $this->assertEquals(0, $response->json('meta.total'));
        $response->assertJsonCount(0, 'data');
    }

    /**
     * Check can pagination preserves other query parameters.
     *
     * @return void
     */
    public function test_can_pagination_preserves_other_query_parameters(): void {
        $user = User::factory()->create();

        Post::factory(5)->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.index",
                ['posts', 'perPage=2', 'sortBy=title', 'sortOrder=asc']));

        $response->assertSuccessful();

        $this->assertEquals(1, $response->json('meta.current_page'));
        $this->assertEquals(1, $response->json('meta.from'));
        $this->assertEquals(2, $response->json('meta.to'));
        $this->assertEquals(2, $response->json('meta.per_page'));
        $this->assertEquals(5, $response->json('meta.total'));
        $response->assertJsonCount(2, 'data');

        $this->assertStringContainsString('&sortBy=title&sortOrder=asc', $response->json('meta.links.1.url'));
    }
}
