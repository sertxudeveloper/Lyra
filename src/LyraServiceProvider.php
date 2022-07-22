<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;

//use SertxuDeveloper\Lyra\Models\LyraUser;

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
     * @param  Router  $router
     * @return void
     */
    public function boot(Router $router): void {
        $this->loadRoutesFrom(dirname(__DIR__).'/routes/api.php');
        $this->loadRoutesFrom(dirname(__DIR__).'/routes/web.php');

        if (config('lyra.auth') === 'lyra') {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/auth.php');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        $loader = AliasLoader::getInstance();
        $loader->alias('Lyra', LyraFacade::class);
        $this->app->singleton('lyra', fn () => new Lyra);

        /** Register configuration files */
        $this->registerConfig();

//        /** Register Auth provider and guard */
//        $this->registerAuth();
    }

//    /**
//     * Register the Lyra auth provider and guard
//     *
//     * @return void
//     */
//    private function registerAuth(): void {
//        /** Register new guard driver */
//        Config::set('auth.guards.lyra', [
//            'driver' => 'session',
//            'provider' => 'lyra',
//        ]);
//
//        /** Register new user provider */
//        Config::set('auth.providers.lyra', [
//            'driver' => 'eloquent',
//            'model' => LyraUser::class,
//        ]);
//    }

    /**
     * Register the Lyra config file
     *
     * @return void
     */
    private function registerConfig(): void {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/lyra.php', 'lyra');
    }
}
