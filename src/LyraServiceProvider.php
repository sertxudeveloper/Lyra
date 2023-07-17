<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Console\InstallCommand;
use SertxuDeveloper\Lyra\Console\ResourceCommand;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;
use SertxuDeveloper\Lyra\Models\LyraUser;

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
     */
    public function boot(Router $router): void {
        /** Load the Lyra views */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lyra');

        /** Register the publishable files */
        $this->configurePublishing();

        /** Register configuration files */
        $this->registerConfig();

        /** Register commands */
        $this->registerCommands();

        /** Register Auth provider and guard */
        $this->registerAuth();

        /** Register routes */
        Lyra::routes();

        /** Register resources in app/Lyra/Resources folder */
        Lyra::resourcesIn(app_path('Lyra/Resources'));
    }

    /**
     * Register any application services.
     */
    public function register(): void {
        $loader = AliasLoader::getInstance();
        $loader->alias('Lyra', LyraFacade::class);
        $this->app->singleton('lyra', fn () => new Lyra);

        $this->loadHelpers();
    }

    /**
     * Configure publishing for the package.
     */
    protected function configurePublishing(): void {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__).'/publishable/config/lyra.php' => config_path('lyra.php'),
            ], 'lyra-config');
        }
    }

    /**
     * Get dynamically the Helpers from the /src/Helpers directory and require_once each file.
     */
    protected function loadHelpers(): void {
        /**
         * @TODO Change the way helpers are registered.
         */
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register the console commands for the package.
     */
    protected function registerCommands(): void {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                ResourceCommand::class,
            ]);
        }
    }

    /**
     * Register the Lyra auth provider and guard
     */
    private function registerAuth(): void {
        /** Register new guard driver */
        Config::set('auth.guards.lyra', [
            'driver' => 'session',
            'provider' => 'lyra',
        ]);

        /** Register new user provider */
        Config::set('auth.providers.lyra', [
            'driver' => 'eloquent',
            'model' => LyraUser::class,
        ]);
    }

    /**
     * Register the Lyra config file
     */
    private function registerConfig(): void {
        $this->mergeConfigFrom(dirname(__DIR__).'/publishable/config/lyra.php', 'lyra');
    }
}
