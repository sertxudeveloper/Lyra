<?php

namespace SertxuDeveloper\Lyra;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\Finder\Finder;

/**
 * Main Lyra package class
 *
 * @version 2.x
 * @package SertxuDeveloper\Lyra
 * @link https://www.github.com/sertxudeveloper/Lyra
 */
class Lyra {

    /** Registered resources */
    private array $resources = [];

    /** Registered callbacks */
    private array $callbacks = [];

    /**
     * Extract Lyra named route from the given request
     *
     * @param Request $request
     * @return string
     */
    static public function getRouteName(Request $request): string {
        return (string)str_replace(config('lyra.routes.api.name'), '', $request->route()->getName());
    }

    /**
     * Get a list of registered resources
     *
     * @return array
     */
    public function getResources(): array {
        return $this->resources;
    }

    /**
     * Get the resource class of the given slug
     *
     * @param string $slug Slug of the wanted resource
     * @return string Class name of the resource
     * @throws ResourceNotFoundException
     */
    public function resourceBySlug(string $slug): string {
        foreach ($this->resources as $class) {
            if ($class::slug() === $slug)
                return $class;
        }

        throw new ResourceNotFoundException;
    }

    /**
     * Register given resources
     *
     * @param string ...$resource The resources to register
     */
    public function resources(string ...$resource): void {
        $this->resources = array_unique(array_merge($this->resources, $resource));
    }

    /**
     * Register resources in a directory
     *
     * @param string $directory Directory to scan for resources
     * @return void
     */
    public function resourcesIn(string $directory): void {
        if (!file_exists($directory)) return;

        $resources = [];

        foreach ((new Finder)->in($directory)->files() as $resource) {
            // Open the resource file and extract the namespace
            $content = file_get_contents($resource->getRealPath());
            $namespace = Str::before(Str::before(Str::after($content, 'namespace '), ';'), ' ');
            $class = Str::before(Str::after($content, 'class '), ' ');
            $resource = "$namespace\\$class";

            // Check if the namespace exists
            if (!class_exists($resource)) continue;

            // Check if the class is a Resource and is not abstract
            $reflection = new ReflectionClass($resource);
            if ($reflection->isSubclassOf(Resource::class) && !$reflection->isAbstract()) {
                $resources[] = $resource;
            }
        }

        $this->resources(...$resources);
    }
}
