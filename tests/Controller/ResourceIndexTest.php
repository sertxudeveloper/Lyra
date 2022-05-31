<?php

namespace SertxuDeveloper\Lyra\Tests\Controller;

use SertxuDeveloper\Lyra\Tests\Lyra\Resources\Users;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceIndexTest extends TestCase {

  /**
   * Check can list a resource.
   * By default, the last inserted will be the first one.
   *
   * @return void
   */
  public function test_can_list_a_resource(): void {
    User::factory(2)->create();
    $user = User::factory()->create();
    $userCount = User::query()->count();

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

    $this->assertEquals($userCount, $response->json('meta.total'));
    $this->assertEquals(Users::$perPageOptions, $response->json('perPageOptions'));

    $this->assertEquals($user->id, $response->json('data.0.key'));

    $fields = $response->json('data.0.fields');
    $nameField = collect($fields)->where('key', 'name')->first();
    $this->assertEquals($user->name, $nameField['value']);

    $response->assertJsonCount($userCount, 'data');
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
    User::factory(2)->create();
    $user = User::factory()->create();

    $deletedUser = User::factory()->create();
    $deletedUser->delete();

    $userCount = User::query()->count();

    $response = $this->withExceptionHandling()
      ->actingAs($user)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users']));

    $response->assertSuccessful();

    $this->assertEquals($userCount, $response->json('meta.total'));
    $this->assertEquals(Users::$perPageOptions, $response->json('perPageOptions'));

    $this->assertEquals($user->id, $response->json('data.0.key'));

    $response->assertJsonCount($userCount, 'data');
  }

  /**
   * Check a soft-deleted resource is shown if requested.
   *
   * @return void
   */
  public function test_includes_soft_deleted_resources_if_requested(): void {
    $user = User::factory()->create();
    User::factory(2)->create();

    $deletedUser = User::factory()->create();
    $deletedUser->delete();

    $userCount = User::withTrashed()->count();

    $response = $this->withExceptionHandling()
      ->actingAs($user)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'trashed=with']));

    $response->assertSuccessful();

    $this->assertEquals($userCount, $response->json('meta.total'));
    $this->assertEquals($deletedUser->id, $response->json('data.0.key'));
    $response->assertJsonCount($userCount, 'data');
  }

  /**
   * Check only soft-deleted resources are shown if requested.
   *
   * @return void
   */
  public function test_show_only_soft_deleted_resources_if_requested(): void {
    $user = User::factory()->create();
    User::factory(2)->create();

    $deletedUser = User::factory()->create();
    $deletedUser->delete();

    $userCount = User::onlyTrashed()->count();

    $response = $this->withExceptionHandling()
      ->actingAs($user)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'trashed=only']));

    $response->assertSuccessful();

    $this->assertEquals($userCount, $response->json('meta.total'));
    $this->assertEquals($deletedUser->id, $response->json('data.0.key'));
    $response->assertJsonCount($userCount, 'data');
  }

  /**
   * Check can order resources.
   *
   * @return void
   */
  public function test_can_order_resources(): void {
    $userA = User::factory()->create(['email' => 'user_a@example.com']);
    $userB = User::factory()->create(['email' => 'user_b@example.com']);
    $userC = User::factory()->create(['email' => 'user_c@example.com']);
    $userCount = User::query()->count();

    /**
     * Check email in ascending order.
     */
    $response = $this->withExceptionHandling()
      ->actingAs($userA)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'sortBy=email', 'sortOrder=asc']));

    $response->assertSuccessful();

    $this->assertEquals($userCount, $response->json('meta.total'));

    $header = $response->json('header');
    $emailHeader = collect($header)->where('key', 'email')->first();
    $this->assertEquals('asc', $emailHeader['order']);

    $response->assertJsonCount($userCount, 'data');
    $this->assertEquals($userA->id, $response->json('data.0.key'));

    $response->assertSeeTextInOrder([$userA->email, $userB->email, $userC->email]);

    /**
     * Check email in descending order.
     */
    $response = $this->withExceptionHandling()
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'sortBy=email', 'sortOrder=desc']));

    $response->assertSuccessful();

    $header = $response->json('header');
    $emailHeader = collect($header)->where('key', 'email')->first();
    $this->assertEquals('desc', $emailHeader['order']);

    $response->assertJsonCount($userCount, 'data');
    $this->assertEquals($userC->id, $response->json('data.0.key'));

    $response->assertSeeTextInOrder([$userC->email, $userB->email, $userA->email]);
  }

  /**
   * Check can limit resources shown per page.
   *
   * @return void
   */
  public function test_can_limit_resources_per_page(): void {
    $user = User::factory()->create();
    User::factory(5)->create();

    $userCount = User::query()->count();

    $response = $this->withExceptionHandling()
      ->actingAs($user)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'perPage=2']));

    $response->assertSuccessful();

    $this->assertEquals(6, $userCount);

    $response->assertJsonCount(2, 'data');

    $this->assertEquals(1, $response->json('meta.current_page'));
    $this->assertEquals(1, $response->json('meta.from'));
    $this->assertEquals(2, $response->json('meta.to'));
    $this->assertEquals(2, $response->json('meta.per_page'));
    $this->assertEquals(6, $response->json('meta.total'));
  }

  /**
   * Check can navigate to next page.
   *
   * @return void
   */
  public function test_can_navigate_resources_per_page(): void {
    $user = User::factory()->create();
    User::factory(5)->create();

    $userCount = User::query()->count();

    $response = $this->withExceptionHandling()
      ->actingAs($user)
      ->getJson(route("$this->API_PREFIX.resources.index", ['users', 'perPage=2', 'page=2']));

    $response->assertSuccessful();

    $this->assertEquals(6, $userCount);

    $response->assertJsonCount(2, 'data');

    $this->assertEquals(2, $response->json('meta.current_page'));
    $this->assertEquals(3, $response->json('meta.from'));
    $this->assertEquals(4, $response->json('meta.to'));
    $this->assertEquals(2, $response->json('meta.per_page'));
    $this->assertEquals(6, $response->json('meta.total'));
  }
}
