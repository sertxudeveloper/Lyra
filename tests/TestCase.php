<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\Lyra\LyraServiceProvider;

abstract class TestCase extends Orchestra {

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
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array {
        return [
            LyraServiceProvider::class,
        ];
    }
}
