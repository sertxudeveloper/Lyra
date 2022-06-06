<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\Lyra\LyraServiceProvider;

abstract class TestCase extends Orchestra {

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        Hash::driver('bcrypt')->setRounds(4);
    }

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
