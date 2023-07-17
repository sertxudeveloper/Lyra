<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Http\Controller;

use SertxuDeveloper\Lyra\Tests\Lyra\Resources\Users;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceCreateControllerTest extends TestCase
{
    /**
     * Check can create a resource.
     */
    public function test_can_create_a_resource(): void {
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->from(route("$this->API_PREFIX.resources.create", ['users']))
            ->post(route("$this->API_PREFIX.resources.store", ['users']), [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password',
            ]);

        $response->assertCreated();

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals(2, User::query()->count());
        $this->assertEquals('John Doe', User::query()->latest('id')->first()->name);
    }

    /**
     * Check can get the create form data.
     */
    public function test_can_get_create_form(): void {
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->get(route("$this->API_PREFIX.resources.create", ['users']));

        $response->assertSuccessful();

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals(null, $response->json('data.key'));
        $this->assertEquals(false, $response->json('data.trashed'));

        $fields = $response->json('data.fields');
        $nameField = collect($fields)->where('key', 'name')->first();
        $this->assertEquals(null, $nameField['value']);
    }
}
