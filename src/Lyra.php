<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\Finder\Finder;

/**
 * Main Lyra package class
 *
 * @version 2.x
 *
 * @link https://www.github.com/sertxudeveloper/Lyra
 */
class Lyra
{
    private static array $resources = [];

    private static array $callbacks = [];

    /**
     * Generate a URL of the given asset
     *
     * @param  string  $file The asset file name
     * @return string The URL of the asset
     */
    public static function asset(string $file): string {
        return dirname(__DIR__)."/publishable/assets/$file";
    }

    /**
     * Get a list of registered resources
     *
     * @return array The list of registered resources
     */
    public static function getResources(): array {
        return static::$resources;
    }

    /**
     * Extract Lyra named route from the given request
     *
     * @param  Request  $request
     * @return string
     */
    public static function getRouteName(Request $request): string {
        return (string) str_replace(config('lyra.routes.api.name'), '', $request->route()->getName());
    }

    /**
     * Get the resource class from the given slug
     *
     * @param  string  $slug Slug of the wanted resource
     * @return string Class name of the resource
     *
     * @throws ResourceNotFoundException
     */
    public static function resourceBySlug(string $slug): string {
        foreach (static::$resources as $class) {
            if ($class::slug() === $slug) {
                return $class;
            }
        }

        throw new ResourceNotFoundException;
    }

    /**
     * Register given resources
     *
     * @param  string  ...$resource The resources to register
     */
    public static function resources(string ...$resource): void {
        static::$resources = array_unique(array_merge(static::$resources, $resource));
    }

    /**
     * Register resources in a directory
     *
     * @param  string  $directory Directory to scan for resources
     * @return void
     */
    public static function resourcesIn(string $directory): void {
        $namespace = app()->getNamespace();
        if (!file_exists($directory)) {
            return;
        }

        $resources = [];

        foreach ((new Finder)->in($directory)->files() as $resource) {
            $path = Str::after($resource->getPathname(), app_path().DIRECTORY_SEPARATOR);
            $resource = $namespace.str_replace(['/', '.php'], ['\\', ''], $path);

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
     *
     * @param  bool  $auth Whether to register authentication routes
     * @return void
     */
    public static function routes(bool $auth = false): void {
        require __DIR__.'/../routes/api.php';
        require __DIR__.'/../routes/web.php';

        if ($auth) {
            require __DIR__.'/../routes/auth.php';
        }
    }

    /**
     * Run the registered Lyra callbacks
     *
     * This method is called automatically when a Lyra controller is called
     *
     * @return void
     */
    public static function runCallbacks() {
        foreach (static::$callbacks as $callback) {
            call_user_func($callback);
        }
    }
}
