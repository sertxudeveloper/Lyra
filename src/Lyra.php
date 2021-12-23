<?php

namespace SertxuDeveloper\Lyra;

use App\Exceptions\ResourceNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\Finder\Finder;

/**
 * Main Lyra package class
 *
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 */
class Lyra {

  public static array $resources = [];
  static private array $callbacks = [];

  /**
   * Generate the URL of the given asset
   *
   * @param $file
   *
   * @return string
   */
  static public function asset($file): string {
    return dirname(__DIR__) . '/publishable/assets/' . $file;
  }

  /**
   * Get the resource class from the given slug
   *
   * @param string $slug
   *
   * @return string
   * @throws ResourceNotFoundException
   */
  static public function resourceBySlug(string $slug): string {
    foreach (static::$resources as $class) {
      if ($class::slug() === $slug)
        return $class;
    }

    throw new ResourceNotFoundException;
  }

  /**
   * Register given resources
   *
   * @param string ...$resource
   */
  static public function resources(string ...$resource): void {
    static::$resources = array_unique(array_merge(static::$resources, $resource));
  }

  /**
   * Register resources in a directory
   *
   * @param $directory
   *
   * @return void
   */
  static public function resourcesIn($directory): void {
    $namespace = app()->getNamespace();
    if (!file_exists($directory))
      return;

    $resources = [];

    foreach ((new Finder)->in($directory)->files() as $resource) {
      $path = Str::after($resource->getPathname(), app_path() . DIRECTORY_SEPARATOR);
      $resource = $namespace . str_replace(['/', '.php'], ['\\', ''], $path);

      try {
        if (is_subclass_of($resource, Resource::class) && !(new ReflectionClass($resource))->isAbstract()) {
          $resources[] = $resource;
        }
      } catch (ReflectionException $e) {
        Log::error($e->getMessage());
        continue;
      }
    }

    static::resources(...$resources);
  }

  /**
   * Register Lyra routes
   */
  static public function routes($auth = false): void {
    require __DIR__ . '/../routes/api.php';
    require __DIR__ . '/../routes/web.php';

    if ($auth) require __DIR__ . '/../routes/auth.php';
  }

  static public function runCallbacks() {
    foreach (static::$callbacks as $callback)
      call_user_func($callback);
  }
}
