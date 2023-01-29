<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;

/**
 * Lyra Service Provider
 *
 * @version 2.x
 *
 * @link https://www.github.com/sertxudeveloper/Lyra
 */
class LyraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
        $this->offerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the Lyra routes.
     *
     * @return void
     */
    protected function registerRoutes(): void {
        Route::group([
            'prefix' => config('lyra.path').'/api',
            'namespace' => 'SertxuDeveloper\Lyra\Http\Controllers',
            'middleware' => config('lyra.middleware', 'web'),
            'as' => 'lyra-api.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });

        Route::group([
            'prefix' => config('lyra.path'),
            'namespace' => 'SertxuDeveloper\Lyra\Http\Controllers',
            'middleware' => config('lyra.middleware', 'web'),
            'as' => 'lyra.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Lyra resources.
     *
     * @return void
     */
    protected function registerResources(): void {
        $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'lyra');

        Blade::componentNamespace('Lyra\\View\\Components', 'lyra');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing(): void {
        $this->publishes([
            dirname(__DIR__).'/public' => public_path('vendor/lyra'),
        ], ['lyra-assets', 'laravel-assets']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        $this->configure();
        $this->registerService();
    }

    /**
     * Set up the configuration for Lyra.
     *
     * @return void
     */
    protected function configure(): void {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/lyra.php', 'lyra'
        );
    }

    /**
     * Set up the resource publishing groups for Lyra.
     *
     * @return void
     */
    protected function offerPublishing(): void {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lyra.php' => config_path('lyra.php'),
            ], 'lyra-config');
        }
    }

    /**
     * Register the Lyra Artisan commands.
     *
     * @return void
     */
    protected function registerCommands(): void {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }

    /**
     * Register Lyra service.
     *
     * @return void
     */
    protected function registerService(): void {
        $loader = AliasLoader::getInstance();
        $loader->alias('Lyra', LyraFacade::class);
        $this->app->singleton('lyra', fn () => new Lyra);
    }
}
