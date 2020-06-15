<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers\MenuController;

/**
 * This class is the main class in the project, here we define the routes and get the Lyra version
 * @version 1.0
 * @package SertxuDeveloper\Lyra
 */
class Lyra {
  const MODE_BASIC = 'basic';
  const MODE_ADVANCED = 'advanced';
  static protected $resources = [];
  static protected $callbacks = [];

  protected static $scripts = [];
  protected static $styles = [];
  protected static $assets = [];
  protected $version;
  protected $filesystem;

  /**
   * Lyra constructor.
   */
  public function __construct() {
    $this->filesystem = app(Filesystem::class);
    $this->findVersion();
  }

  static public function allAssets() {
    return self::$assets;
  }

  static public function allScripts() {
    return self::$scripts;
  }

  static public function allStyles() {
    return self::$styles;
  }

  static public function asset($name, $style) {
    self::$assets[$name] = $style;
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
      return array_search(self::auth()->user()->email, config('lyra.authorized_users')) !== false;
    }
  }

  static public function getResources() {
    return self::$resources;
  }

  static public function resources(array $resources) {
    self::$resources = array_merge(self::$resources, $resources);
  }

  static public function route($prefix, $route) {
    Route::middleware(['web', 'lyra-api'])
      ->prefix(config('lyra.routes.api.prefix') . '/components/' . $prefix)
      ->group($route);
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

  static public function runCallbacks() {
    foreach (self::$callbacks as $callback) call_user_func($callback);
  }

  static public function script($name, $script) {
    self::$scripts[$name] = $script;
  }

  static public function serving($callback) {
    self::$callbacks[] = $callback;
  }

  static public function style($name, $style) {
    self::$styles[$name] = $style;
  }

  /**
   * This method returns all the items the user has access from the menu
   * @return array
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
}
