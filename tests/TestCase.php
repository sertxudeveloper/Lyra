<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use SertxuDeveloper\Lyra\LyraServiceProvider;

abstract class TestCase extends OrchestraTestCase {

  /**
   * Setup the test environment.
   *
   * @return void
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Define environment setup.
   *
   * @param Application $app
   * @return void
   */
  protected function getEnvironmentSetUp($app): void {
    //
  }

  /**
   * Get package providers.
   *
   * @param Application $app
   * @return array<int, class-string>
   */
  protected function getPackageProviders($app): array {
    return [
      LyraServiceProvider::class,
    ];
  }
}
