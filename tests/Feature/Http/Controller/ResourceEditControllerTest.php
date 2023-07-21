<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Http\Controller;

use Illuminate\Http\Response;
use SertxuDeveloper\Lyra\Tests\Lyra\Resources\Users;
use SertxuDeveloper\Lyra\Tests\Models\User;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceEditControllerTest extends TestCase
{
    /**
     * Check can edit a resource.
     */
    public function test_can_edit_a_resource(): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas($otherUser->getTable(), [
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->from(route("$this->API_PREFIX.resources.edit", ['users', $otherUser]))
            ->post(route("$this->API_PREFIX.resources.update", ['users', $otherUser]), [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'updated_at' => $otherUser->updated_at->toIso8601String(),
            ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas($otherUser->getTable(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseMissing($user->getTable(), [
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals(2, User::query()->count());
        $this->assertEquals('John Doe', User::query()->latest('id')->first()->name);
    }

    /**
     * Check can't edit a resource if the model has been modified since the retrieval.
     */
    public function test_cant_edit_a_resource_that_has_been_modified(): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas($otherUser->getTable(), [
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $oldUpdatedAt = $otherUser->updated_at;

        $this->travelTo(now()->addMinute());

        $otherUser->name = 'Sertxu Dev';
        $otherUser->save();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->from(route("$this->API_PREFIX.resources.edit", ['users', $otherUser]))
            ->post(route("$this->API_PREFIX.resources.update", ['users', $otherUser]), [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password',
                'updated_at' => $oldUpdatedAt->toIso8601String(),
            ]);

        $response->assertStatus(Response::HTTP_CONFLICT);

        $this->assertDatabaseHas($otherUser->getTable(), [
            'name' => 'Sertxu Dev',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseMissing($user->getTable(), [
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseMissing($user->getTable(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);
    }

    /**
     * Check can get the create form data.
     */
    public function test_can_get_edit_form(): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->get(route("$this->API_PREFIX.resources.edit", ['users', $otherUser]));

        $response->assertSuccessful();

        $this->assertIsArray($response->json('data'));
        $this->assertIsArray($response->json('labels'));

        $this->assertEquals(Users::label(), $response->json('labels.plural'));
        $this->assertEquals(Users::singular(), $response->json('labels.singular'));

        $this->assertEquals($otherUser->getKey(), $response->json('data.key'));
        $this->assertEquals($otherUser->trashed(), $response->json('data.trashed'));

        $fields = collect($response->json('data.panels'))->pluck('fields')->flatten(1);
        $nameField = collect($fields)->where('key', 'name')->first();
        $this->assertEquals($otherUser->name, $nameField['value']);
    }
}
