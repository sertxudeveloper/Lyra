<?php

namespace SertxuDeveloper\Lyra\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * Lyra facade.
 *
 * @version 2.x
 *
 * @method static string getRouteName(Request $request)
 * @method static array getResources()
 * @method static string resourceBySlug(string $slug)
 * @method static void resources(string ...$resource)
 * @method static void resourcesIn(string $directory)
 * @method static void runCallbacks()
 *
 * @see \SertxuDeveloper\Lyra\Lyra
 */
class Lyra extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lyra';
    }
}
