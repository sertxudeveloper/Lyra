<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\Finder\Finder;

/**
 * Main Lyra package class
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 */
class Lyra {

  private static array $callbacks = [];

  static public function routes() {
    require __DIR__ . '/../routes/api.php';
    require __DIR__ . '/../routes/web.php';
  }

  static public function runCallbacks() {
    foreach (self::$callbacks as $callback) call_user_func($callback);
  }

  /**
   * @param $directory
   * @return array
   * @throws \ReflectionException
   */
  static public function resourcesIn($directory): array {
    $namespace = app()->getNamespace();
    if (!file_exists($directory)) return [];

    $resources = collect();

    foreach ((new Finder)->in($directory)->files() as $resource) {
      $path = Str::after($resource->getPathname(), app_path() . DIRECTORY_SEPARATOR);
      $resource = $namespace . str_replace(['/', '.php'], ['\\', ''], $path);

      if (is_subclass_of($resource, Resource::class) &&
        !(new \ReflectionClass($resource))->isAbstract()) {
        $resources->push($resource);
      }
    }

    return $resources->all();
  }

  static public function asset($file): string {
    return dirname(__DIR__) . '/publishable/assets/' . $file;
  }
}
