<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\LyraServiceProvider;
use SertxuDeveloper\Lyra\Tests\Lyra\Resources;
use SertxuDeveloper\Lyra\Tests\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public string $API_PREFIX = '';

    /**
     * Setup the test environment.
     */
    public function setUp(): void {
        parent::setUp();

        Hash::driver('bcrypt')->setRounds(4);

        $this->API_PREFIX = config('lyra.routes.api.prefix');

        $this->loadMigrations();

        Lyra::resources(
            Resources\Users::class,
            Resources\Posts::class,
            Resources\Tags::class,
            Resources\Categories::class,
        );
    }

    protected function authenticate(Authenticatable $user = null): Authenticatable {
        $this->actingAs($user ??= User::factory()->create());

        return $user;
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app): void {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array {
        return [
            LyraServiceProvider::class,
        ];
    }

    /**
     * Load the migrations for the test environment.
     */
    protected function loadMigrations(): void {
        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));
    }
}
