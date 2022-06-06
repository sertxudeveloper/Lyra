<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;

/**
 * Lyra Service Provider
 *
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 * @link https://www.github.com/sertxudeveloper/Lyra
 */
class LyraServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        $loader = AliasLoader::getInstance();
        $loader->alias('Lyra', LyraFacade::class);
        $this->app->singleton('lyra', fn () => new Lyra());
    }
}
