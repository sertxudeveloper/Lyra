<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\TestCase;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\LyraServiceProvider;
use SertxuDeveloper\Lyra\Tests\Lyra\Resources;

abstract class IntegrationTest extends TestCase {

  public string $API_PREXIX = '';

  /**
   * Setup the test environment.
   *
   * @return void
   */
  protected function setUp(): void {
    parent::setUp();

    Hash::driver('bcrypt')->setRounds(4);

    $this->API_PREXIX = config('lyra.routes.api.prefix');

    $this->loadMigrations();

    Lyra::resources(
      Resources\Users::class,
      Resources\Posts::class,
      Resources\Tags::class,
      Resources\Categories::class,
    );
  }

  /**
   * Define environment setup.
   *
   * @param Application $app
   * @return void
   */
  protected function getEnvironmentSetUp($app) {
    $app['config']->set('database.default', 'sqlite');

    $app['config']->set('database.connections.sqlite', [
      'driver' => 'sqlite',
      'database' => ':memory:',
      'prefix' => '',
    ]);
  }

  /**
   * Get package providers.
   *
   * @param Application $app
   * @return array<int, class-string>
   */
  protected function getPackageProviders($app): array {
    return [
      ConsoleServiceProvider::class,
      LyraServiceProvider::class,
      TestServiceProvider::class,
    ];
  }

  /**
   * Load the migrations for the test environment.
   *
   * @return void
   */
  protected function loadMigrations(): void {
    $this->loadMigrationsFrom([
      '--database' => 'sqlite',
      '--realpath' => true,
      '--path' => realpath(__DIR__ . '/Migrations'),
    ]);
  }

  /**
   * Authenticate as an anonymous user.
   *
   * @return $this
   */
  protected function authenticate() {

//    $this->actingAs($this->authenticatedAs = Mockery::mock(Authenticatable::class));
//
//    $this->authenticatedAs->shouldReceive('getAuthIdentifier')->andReturn(1);

//    return $this;
  }
}
