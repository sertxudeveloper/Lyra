<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use SertxuDeveloper\Lyra\Http\Controllers\MenuController;
use SertxuDeveloper\Lyra\Models\MenuItem;
use SertxuDeveloper\Lyra\Models\Permission;

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
    if (config('lyra.authenticator') === 'lyra' && auth()->guard('lyra')->user()) {
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
    if (!is_null($this->version)) {
      return;
    }

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
    if (config('lyra.authenticator') == 'basic') {
      return auth();
    } else if (config('lyra.authenticator') == 'lyra') {
      return auth()->guard('lyra');
    }
    return abort(403);
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
}
