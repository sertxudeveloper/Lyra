<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;

class LyraServiceProvider extends ServiceProvider {

  /**
   * Bootstrap any application services.
   *
   * @param Router $router
   * @return void
   */
  public function boot(Router $router) {
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lyra');

    $this->registerConfig();

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
  public function register() {
    $loader = AliasLoader::getInstance();
    $loader->alias('Lyra', LyraFacade::class);

    $this->app->singleton('lyra', function () {
      return new Lyra();
    });

    $this->loadHelpers();
  }

  /**
   * Get dynamically the Helpers from the /src/Helpers directory and require_once each file.
   */
  protected function loadHelpers() {
    foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
      require_once $filename;
    }
  }

  /**
   * Register the Lyra config file
   */
  private function registerConfig() {
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/lyra.php', 'lyra');
  }
}
