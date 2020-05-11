<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Middleware\LyraAdminMiddleware;
use SertxuDeveloper\Lyra\Facades\Lyra as LyraFacade;
use SertxuDeveloper\Lyra\Http\Middleware\LyraApiAdminMiddleware;

class LyraServiceProvider extends ServiceProvider {

  /**
   * Bootstrap services.
   *
   * @param \Illuminate\Routing\Router $router
   * @return void
   */
  public function boot(Router $router) {
    $router->aliasMiddleware('lyra', LyraAdminMiddleware::class);
    $router->aliasMiddleware('lyra-api', LyraApiAdminMiddleware::class);
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lyra');

    $this->registerConfigs();
    $this->registerPublishable();
    $this->registerResources();

    $this->loadTranslationsFrom(__DIR__ . '/../publishable/lang/', 'lyra');

    if ($this->app->runningInConsole()) $this->registerCommands();

    Lyra::routes();

    Lyra::asset('lyra-icon', __DIR__ . '/../publishable/assets/images/icon.png');
    Lyra::asset('lyra-logo', __DIR__ . '/../publishable/assets/images/logo.png');

    Lyra::style('lyra', __DIR__ . '/../publishable/assets/css/main.css');
    Lyra::script('lyra', __DIR__ . '/../publishable/assets/js/app.js');
    $this->registerComponents();
  }

  /**
   * Register services.
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
    $this->registerPolicies();
  }

  /**
   * Get dynamically the Helpers from the /src/Helpers directory and require_once each file.
   */
  protected function loadHelpers() {
    foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
      require_once $filename;
    }
  }

  private function generateComponentsMap($components) {
    return collect($components)->mapWithKeys(function ($item) {
      if (isset($item['items'])) {
        return $this->generateComponentsMap($item['items']);
      } else {
        if (!isset($item['component'])) return [];
        if (!isset($item['key'])) $item['key'] = Str::snake($item['name']);
        return [$item['key'] => $item['component']];
      }
    });
  }

  private function generateResourcesMap($resources) {
    return collect($resources)->mapWithKeys(function ($item) {
      if (isset($item['items'])) {
        return $this->generateResourcesMap($item['items']);
      } else {
        if (!isset($item['resource'])) return [];
        if (!isset($item['key'])) $item['key'] = Str::snake($item['name']);
        return [$item['key'] => $item['resource']];
      }
    });
  }

  /**
   * Register terminal commands
   */
  private function registerCommands() {
    $this->commands([
      Commands\CardMakeCommand::class,
      Commands\DashboardMakeCommand::class,
      Commands\InstallCommand::class,
      Commands\UpdateCommand::class,
      Commands\PermissionsMakeCommand::class,
      Commands\ResourceMakeCommand::class,
      Commands\RoleMakeCommand::class,
      Commands\UserMakeCommand::class,
      Commands\ComponentMakeCommand::class
    ]);
  }

  private function registerComponents() {
    $components = config('lyra.menu');
    $components = $this->generateComponentsMap($components)->toArray();
    foreach ($components as $component) (new $component)->boot();
  }

  private function registerConfigs() {
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/lyra.php', 'lyra');
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/auth/guards.php', 'auth.guards.lyra');
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/auth/passwords.php', 'auth.passwords.lyra');
    $this->mergeConfigFrom(dirname(__DIR__) . '/publishable/config/auth/providers.php', 'auth.providers.lyra');
  }

  private function registerPublishable() {
    $packagePath = __DIR__ . '/..';

    $publishable = [
      "lyra-views" => [
        "${packagePath}/resources/views" => base_path('resources/views/vendor/lyra')
      ],
      "lyra-config" => [
        "${packagePath}/publishable/config/lyra.php" => config_path('lyra.php')
      ],
      'lyra-migrations' => [
        "${packagePath}/publishable/database/migrations/" => database_path('migrations/lyra'),
      ],
    ];

    foreach ($publishable as $group => $paths) {
      $this->publishes($paths, $group);
    }
  }

  private function registerResources() {
    $resources = config('lyra.menu');
    $resources = $this->generateResourcesMap($resources)->toArray();
    Lyra::resources($resources);
  }

}
