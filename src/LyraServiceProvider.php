<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;
use SertxuDeveloper\Lyra\Models\LyraUser;

/**
 * Lyra Service Provider
 *
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 * @link https://www.github.com/sertxudeveloper/Lyra
 */
class LyraServiceProvider extends ServiceProvider {

  /**
   * Bootstrap any application services.
   *
   * @param Router $router
   * @return void
   */
  public function boot(Router $router): void {
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lyra');

    /** Register configuration files */
    $this->registerConfig();

    /** Register Auth provider and guard */
    $this->registerAuth();

    /** Register routes */
    Lyra::routes();

    /** Register resources in app/Lyra/Resources folder */
    Lyra::resourcesIn(app_path('Lyra/Resources'));
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register(): void {
    $loader = AliasLoader::getInstance();
    $loader->alias('Lyra', LyraFacade::class);

    $this->app->singleton('lyra', function () {
      return new Lyra();
    });

    $this->loadHelpers();
  }

  /**
   * Get dynamically the Helpers from the /src/Helpers directory and require_once each file.
   *
   * @return void
   */
  protected function loadHelpers(): void {
    foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
      require_once $filename;
    }
  }

  /**
   * Register the Lyra auth provider and guard
   *
   * @return void
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
   *
   * @return void
   */
  private function registerConfig(): void {
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/lyra.php', 'lyra');
  }
}
