<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\Lyra\Facades\Lyra;
use SertxuDeveloper\Lyra\LyraServiceProvider;

abstract class TestCase extends Orchestra {

    protected string $API_PREFIX = '';

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations(): void {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void {
        $this->API_PREFIX = config('lyra.routes.api.prefix');
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

    /**
     * Register the default testing resources.
     *
     * @return void
     */
    protected function registerDefaultResources(): void {
        Lyra::resourcesIn(__DIR__ . '/Resources');
    }
}
