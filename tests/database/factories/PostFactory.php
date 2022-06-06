<?php

namespace SertxuDeveloper\Lyra\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Tests\Models\Post;

class PostFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $path = storage_path('app/public/images');
        if (!is_dir($path)) mkdir($path, recursive: true);

        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->paragraph,
            'body' => $this->faker->randomHtml,
            'poster' => $this->faker->image($path, fullPath: false),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
