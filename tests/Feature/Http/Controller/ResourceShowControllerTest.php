<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Http\Controller;

use SertxuDeveloper\Lyra\Tests\Lyra\Resources\Users;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceShowControllerTest extends TestCase
{
    /**
     * Check can show a resource.
     *
     * @return void
     */
    public function test_can_show_a_resource(): void {
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.show", ['users', $user]));

        $response->assertSuccessful();

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals($user->id, $response->json('data.key'));
        $this->assertEquals(false, $response->json('data.trashed'));

        $fields = $response->json('data.fields');
        $nameField = collect($fields)->where('key', 'name')->first();
        $this->assertEquals($user->name, $nameField['value']);
    }

    /**
     * Check can show trashed resource.
     *
     * @return void
     */
    public function test_can_show_a_trashed_resource(): void {
        $user = User::factory()->create();
        $trashed = User::factory()->create();
        $trashed->delete();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->getJson(route("$this->API_PREFIX.resources.show", ['users', $trashed]));

        $response->assertSuccessful();

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals($trashed->id, $response->json('data.key'));
        $this->assertEquals(true, $response->json('data.trashed'));

        $fields = $response->json('data.fields');
        $nameField = collect($fields)->where('key', 'name')->first();
        $this->assertEquals($trashed->name, $nameField['value']);
    }
}
