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
     *
     * @return void
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

    protected function authenticate(?Authenticatable $user = null): Authenticatable {
        $this->actingAs($user ??= User::factory()->create());

        return $user;
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
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

//
    //  public string $API_PREFIX = '';
//
    //  /**
    //   * Setup the test environment.
    //   *
    //   * @return void
    //   */
    //  protected function setUp(): void {
//    parent::setUp();
//
//    Hash::driver('bcrypt')->setRounds(4);
//
//    $this->API_PREFIX = config('lyra.routes.api.prefix');
//
//    $this->loadMigrations();
//
//    Lyra::resources(
//      Resources\Users::class,
//      Resources\Posts::class,
//      Resources\Tags::class,
//      Resources\Categories::class,
//    );
    //  }
//
    //  /**
    //   * Define environment setup.
    //   *
    //   * @param Application $app
    //   * @return void
    //   */
    //  protected function getEnvironmentSetUp($app): void {
//    $app['config']->set('database.default', 'sqlite');
//
//    $app['config']->set('database.connections.sqlite', [
//      'driver' => 'sqlite',
//      'database' => ':memory:',
//      'prefix' => '',
//    ]);
    //  }
//
    //  /**
    //   * Get package providers.
    //   *
    //   * @param Application $app
    //   * @return array<int, class-string>
    //   */
    //  protected function getPackageProviders($app): array {
//    return [
//      LyraServiceProvider::class,
//      TestServiceProvider::class,
//    ];
    //  }
//
    //  /**
    //   * Load the migrations for the test environment.
    //   *
    //   * @return void
    //   */
    //  protected function loadMigrations(): void {
//    $this->loadMigrationsFrom([
//      '--database' => 'sqlite',
//      '--realpath' => true,
//      '--path' => realpath(__DIR__ . '/migrations'),
//    ]);
    //  }

    /**
     * Load the migrations for the test environment.
     *
     * @return void
     */
    protected function loadMigrations(): void {
        $this->loadMigrationsFrom(realpath(__DIR__.'/migrations'));
    }
}
