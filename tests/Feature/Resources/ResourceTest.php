<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Resources;

use SertxuDeveloper\Lyra\Pagination\LengthAwarePaginator;
use SertxuDeveloper\Lyra\Resources\ResourceCollection;
use SertxuDeveloper\Lyra\Tests\Models\Post;
use SertxuDeveloper\Lyra\Tests\Resources\Posts;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ResourceTest extends TestCase {

    /**
     * Check if a resources can be initialized with a new model.
     *
     * @return void
     */
    public function test_a_resource_can_be_initialized_with_a_new_model(): void {
        $class = Posts::class;
        $model = new $class::$model;
        $resource = $class::make($model);

        $this->assertInstanceOf(Posts::class, $resource);
    }

    /**
     * Check if a resources can be initialized with an existing model.
     *
     * @return void
     */
    public function test_a_resource_can_be_initialized_with_an_existing_model(): void {
        $post = Post::factory()->create();

        $class = Posts::class;
        $resource = $class::make($post);

        $this->assertInstanceOf(Posts::class, $resource);
    }

    /**
     * Check if a resources can be initialized with a collection.
     *
     * @return void
     */
    public function test_a_resource_can_be_initialized_with_a_collection(): void {
        Post::factory(5)->create();
        $class = Posts::class;

        $currentPage = 1;
        $perPage = 2;
        $options = ['path' => '/', 'pageName' => 'page'];

        $query = $class::$model::query();
        $total = $query->toBase()->getCountForPagination();
        $items = $total ? $query->forPage($currentPage, $perPage)->get() : (new $class::$model)->newCollection();

        $pagination = new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
        $response = ResourceCollection::make($pagination, $class);

        $this->assertInstanceOf(ResourceCollection::class, $response);

        $this->assertEquals(2, $response->count());
        $this->assertEquals(2, $response->perPage());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(5, $response->total());
        $this->assertEquals(3, $response->lastPage());
        $this->assertEquals(1, $response->firstItem());
        $this->assertEquals(2, $response->lastItem());

        $this->assertInstanceOf(Posts::class, $response->first());

        $this->assertEquals($items[0]->id, $response->first()->id);
        $this->assertEquals($items[1]->id, $response->last()->id);
    }

    /**
     * Check if it can get the resource label.
     *
     * @return void
     */
    public function test_it_can_get_the_resource_label(): void {
        $class = Posts::class;
        $this->assertEquals('Posts', $class::label());
    }

    /**
     * Check if it can get a new instance of the provided model.
     *
     * @return void
     */
    public function test_it_can_get_a_new_instance_of_the_provided_model(): void {
        $class = Posts::class;
        $this->assertInstanceOf($class::$model, $class::newModel());
    }

    /**
     * Check if it can get the singular label of the resource.
     *
     * @return void
     */
    public function test_it_can_get_the_singular_label_of_the_resource(): void {
        $class = Posts::class;
        $this->assertEquals('Post', $class::singular());
    }
}
