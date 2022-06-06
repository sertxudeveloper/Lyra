<?php

namespace SertxuDeveloper\Lyra\Tests\Unit;

use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Facades\Lyra;
use SertxuDeveloper\Lyra\Tests\Resources\Posts;
use SertxuDeveloper\Lyra\Tests\Resources\Tags;
use SertxuDeveloper\Lyra\Tests\Resources\Users;
use SertxuDeveloper\Lyra\Tests\TestCase;

class LyraTest extends TestCase
{
    /**
     * Check can get a resource by slug.
     *
     * @return void
     */
    public function test_can_get_a_resource_by_slug(): void
    {
        $this->assertEmpty(Lyra::getResources());

        Lyra::resources(Users::class, Posts::class);

        $this->assertEquals(Users::class, Lyra::resourceBySlug('users'));
        $this->assertEquals(Posts::class, Lyra::resourceBySlug('posts'));
    }

    /**
     * Check can manually register a resource.
     *
     * @return void
     */
    public function test_can_manually_register_a_resource(): void
    {
        $this->assertEmpty(Lyra::getResources());

        Lyra::resources(Users::class);

        $this->assertCount(1, Lyra::getResources());
        $this->assertContains(Users::class, Lyra::getResources());
        $this->assertNotContains(Posts::class, Lyra::getResources());
        $this->assertNotContains(Tags::class, Lyra::getResources());
    }

    /**
     * Check can register multiple resources at once.
     *
     * @return void
     */
    public function test_can_register_multiple_resources(): void
    {
        $this->assertEmpty(Lyra::getResources());

        Lyra::resources(Users::class, Posts::class);

        $this->assertCount(2, Lyra::getResources());
        $this->assertContains(Users::class, Lyra::getResources());
        $this->assertContains(Posts::class, Lyra::getResources());
        $this->assertNotContains(Tags::class, Lyra::getResources());
    }

    /**
     * Check can register resources inside a directory.
     *
     * @return void
     */
    public function test_can_register_resources_inside_folder(): void
    {
        $this->assertEmpty(Lyra::getResources());

        Lyra::resourcesIn('tests/Resources');

        $this->assertCount(3, Lyra::getResources());
        $this->assertContains(Users::class, Lyra::getResources());
        $this->assertContains(Posts::class, Lyra::getResources());
        $this->assertContains(Tags::class, Lyra::getResources());
    }

    /**
     * Check cannot get a resource by slug if it doesn't exist.
     *
     * @return void
     */
    public function test_cannot_get_a_resource_by_slug_if_it_doesnt_exist(): void
    {
        $this->assertEmpty(Lyra::getResources());

        Lyra::resources(Users::class, Posts::class);

        $this->expectException(ResourceNotFoundException::class);
        Lyra::resourceBySlug(Tags::slug());
    }
}
