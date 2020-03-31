<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers\MenuController;

/**
 * This class is the main class in the project, here we define the routes and get the Lyra version
 * @version 1.0
 * @package SertxuDeveloper\Lyra
 */
class Lyra {
  protected $version;
  protected $filesystem;
  static protected $resources = [];
  static protected $observables;
  public static $scripts = [];
  public static $styles = [];

  const MODE_BASIC = 'basic';
  const MODE_ADVANCED = 'advanced';

  /**
   * Lyra constructor.
   */
  public function __construct() {
    $this->filesystem = app(Filesystem::class);
    $this->findVersion();
  }

  /**
   * This method defines the web routes for the Lyra package.
   * @return void
   * @version 1.0
   */
  static public function routes() {
    require __DIR__ . '/../routes/api.php';
    require __DIR__ . '/../routes/web.php';
  }

  /**
   * This method returns the url to the preferred theme by the user
   * @return string
   */
  public function getPreferredTheme() {
    if (config('lyra.authenticator') === self::MODE_ADVANCED && auth()->guard('lyra')->user()) {
      return lyra_asset("css/" . auth()->guard('lyra')->user()->preferred_theme . ".css");
    } else {
      if (Cookie::get('preferred_theme')) {
        return lyra_asset("css/" . Cookie::get('preferred_theme') . ".css");
      } else {
        return lyra_asset("css/default.css");
      }
    }
  }

  /**
   * This method returns all the items the user has access from the menu
   * @return string
   */
  public function getMenuItems() {
    return (new MenuController())->getMenu();
  }

  /**
   * This method returns the current installed version
   * @return string
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * This method is used by the __construct to define the Lyra version looking in the composer.lock file
   * @return void
   */
  protected function findVersion() {
    if (!is_null($this->version)) return;

    if ($this->filesystem->exists(base_path('composer.lock'))) {
      // Get the composer.lock file
      $file = json_decode($this->filesystem->get(base_path('composer.lock')));

      // Loop through all the packages and get the version of lyra
      foreach ($file->packages as $package) {
        if ($package->name == 'sertxudeveloper/lyra') {
          $this->version = $package->version;
          break;
        }
      }
    }
  }

  static public function auth() {
    if (config('lyra.authenticator') === self::MODE_BASIC) {
      return auth();
    } else if (config('lyra.authenticator') === self::MODE_ADVANCED) {
      return auth()->guard('lyra');
    }
    return abort(403);
  }

  static public function broker() {
    if (config('lyra.authenticator') === self::MODE_BASIC) {
      return Password::broker();
    } else if (config('lyra.authenticator') === self::MODE_ADVANCED) {
      return Password::broker('lyra');
    }
    return abort(403);
  }

  /**
   * Check if the current user can do the requested $action in the current $resource
   *
   * @param $action
   * @param $resource
   *
   * @return bool
   */
  public static function checkPermission($action, $resource) {
    if (config('lyra.authenticator') === Lyra::MODE_ADVANCED) {
      return self::auth()->user()->hasPermission($action, $resource);
    } else {
      return true;
    }
  }

  static public function resources(array $resources) {
    self::$resources = array_merge(self::$resources, $resources);
  }

  static public function getResources() {
    return self::$resources;
  }

  static public function observables($observables) {
    self::$observables = $observables;
  }

  static public function runObservables() {
    if (self::$observables) call_user_func(self::$observables);
  }

  static public function script($script) {
    self::$scripts[] = $script;
  }

  static public function style($style) {
    self::$styles[] = $style;
  }

  static public function route($prefix, $route) {
    Route::middleware(['web', 'lyra-api'])
      ->prefix(config('lyra.routes.api.prefix') . '/components' . $prefix)
      ->group($route);
  }
}
