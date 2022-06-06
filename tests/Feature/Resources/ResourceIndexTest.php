<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Resources;

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Facades\Lyra;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\Resources\Users;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceIndexTest extends TestCase {

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
}
