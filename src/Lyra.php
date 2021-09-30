<?php

namespace SertxuDeveloper\Lyra;

use Exception;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\Finder\Finder;

/**
 * Main Lyra package class
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 */
class Lyra {

  static private array $callbacks = [];
  public static array $resources = [];

  /**
   * Register Lyra routes
   */
  static public function routes() {
    require __DIR__ . '/../routes/api.php';
    require __DIR__ . '/../routes/web.php';
  }

  static public function runCallbacks() {
    foreach (static::$callbacks as $callback) call_user_func($callback);
  }

  /**
   * Register resources in a directory
   *
   * @param $directory
   * @return void
   */
  static public function resourcesIn($directory): void {
    $namespace = app()->getNamespace();
    if (!file_exists($directory)) return;

    $resources = [];

    foreach ((new Finder)->in($directory)->files() as $resource) {
      $path = Str::after($resource->getPathname(), app_path() . DIRECTORY_SEPARATOR);
      $resource = $namespace . str_replace(['/', '.php'], ['\\', ''], $path);

      try {
        if (is_subclass_of($resource, Resource::class) &&
            !(new \ReflectionClass($resource))->isAbstract()) {
          array_push($resources, $resource);
        }
      } catch (\ReflectionException $e) {
        continue;
      }
    }

    static::resources(...$resources);
  }

  /**
   * Register given resources
   *
   * @param string ...$resource
   */
  static public function resources(string ...$resource): void {
    static::$resources = array_unique(
      array_merge(static::$resources, $resource)
    );
  }

  /**
   * Get the resource class from the given slug
   *
   * @param string $slug
   * @return string
   * @throws Exception
   */
  static public function searchResource(string $slug): string {
    foreach (static::$resources as $class) {
      if ($class::slug() == $slug) return $class;
    }

    throw new Exception('Resource not found');
  }

  /**
   * Generate the URL of the given asset
   *
   * @param $file
   * @return string
   */
  static public function asset($file): string {
    return dirname(__DIR__) . '/publishable/assets/' . $file;
  }
}
